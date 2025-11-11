<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once 'Templates.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    /**
     * Class Utils
     * 
     * A comprehensive utility class providing general-purpose functions 
     * for the Football Pitch Booking Management System.
     * 
     * It includes:
     * - General helpers (random number generation, date handling)
     * - JWT handling (generation, verification, authentication)
     * - Template rendering for emails
     * - PHPMailer integration for sending verification and welcome emails
     * 
     * @package FootballPitchBooking
     * @author  Amos Nyirenda
     * @version 1.0
     */
    class Utils {

        ################################################################
        #                      General Util functions                  #
        ################################################################

        /**
         * Generate a random integer of a specific length.
         *
         * @param int $length The number of digits (default is 8).
         * 
         * @return int Random integer of the specified length.
         */
        public static function generateRandomInt(int $length = 8): int {
            $min = (int) str_pad('1', $length, '0');   
            $max = (int) str_pad('', $length, '9');    
            return random_int($min, $max);
        }

        ################################################################
        #                      JWT Token Specific                      #
        ################################################################

        /**
         * Generate a JWT token.
         *
         * @param array  $payload Data to encode inside the JWT.
         * @param string $secret  Secret key used for encoding.
         * @param int    $expiry  Expiration time in seconds (default 1 hour).
         * 
         * @return string Encoded JWT.
         */
        public static function generateJWT(array $payload, string $secret, int $expiry = 3600): string {
            $issuedAt = time();
            $payload = array_merge($payload, [
                'iat' => $issuedAt,
                'exp' => $issuedAt + $expiry
            ]);

            return JWT::encode($payload, $secret, 'HS256');
        }

        /**
         * Verify and decode a JWT token.
         *
         * @param string $token  The JWT to verify.
         * @param string $secret Secret key.
         * 
         * @return object|false  Decoded payload if valid, false otherwise.
         */
        public static function verifyJWT(string $token, string $secret): object|false {
            try {
                return JWT::decode($token, new Key($secret, 'HS256'));
            } catch (Exception $e) {
                return false;
            }
        }

        /**
         * Generate a token expiration timestamp.
         *
         * @param int $minutes Number of minutes until expiration.
         * 
         * @return string Expiration timestamp in 'Y-m-d H:i:s' format.
         */
        public static function generateVerificationTokenExpiry(int $minutes): string {
            $expiry = new DateTime();
            $expiry->modify("+{$minutes} minutes");
            return $expiry->format('Y-m-d H:i:s');
        }

        /**
         * Extract a JWT from the Authorization header.
         * 
         * @return string|false JWT string if present, false otherwise.
         */
        public static function getBearerToken() {
            $headers = getallheaders();
            if (!isset($headers['Authorization'])) {
                return false;
            }

            $matches = [];
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                return $matches[1];
            }

            return false;
        }

        /**
         * Authenticate the current request using JWT.
         *
         * @param string $secret Secret key for decoding.
         * 
         * @return object|false  Decoded user data if valid, false otherwise.
         */
        public static function authenticateRequest(string $secret) {
            $token = self::getBearerToken();
            if (!$token) {
                http_response_code(401);
                echo json_encode(['error' => 'Authorization token missing']);
                exit;
            }

            $decoded = self::verifyJWT($token, $secret);
            if (!$decoded) {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid or expired token']);
                exit;
            }

            return $decoded;
        }

        ################################################################
        #               Template Generating Functions                  #
        ################################################################

        /**
         * Generate the HTML content for the verification email.
         *
         * @param string $name              The recipient's full name.
         * @param string $verification_code The unique verification code sent to the user.
         * @param int    $expiry            The number of minutes before the verification code expires.
         * 
         * @return string Rendered HTML email content for verification.
         */
        public static function getVerificationEmailTemplate(string $name, string $verification_code, int $expiry): string {
            $vars = [
                'name' => $name, 
                'expiry' => $expiry,
                'verification_code' => $verification_code
            ];
            return Template::render(Template::$ACCOUNT_VERIFICATION_EMAIL_TEMPLATE, $vars);
        }

        /**
         * Generate the HTML content for the welcome email.
         *
         * @param string $name The recipient's full name.
         * 
         * @return string Rendered HTML email content for the welcome message.
         */
        public static function getWelcomeEmailTemplate(string $name): string {
            $vars = [
                'name' => $name
            ];

            return Template::render(Template::$WELCOME_EMAIL_TEMPLATE, $vars);
        }

        ################################################################
        #                      Mail Utility Functions                  #
        ################################################################

        /**
         * Create and configure a PHPMailer instance for sending emails.
         *
         * @return PHPMailer Configured PHPMailer object ready for sending.
         */
        public static function createMailer(): PHPMailer {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = MAIL_HOST; 
                $mail->SMTPAuth   = true;
                $mail->Username   = MAIL_USERNAME;
                $mail->Password   = MAIL_PASSWORD;
                $mail->SMTPSecure = MAIL_ENCRYPTION ?? 'tls';
                $mail->Port       = MAIL_PORT ?? 587;
                $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME ?? 'FootBall Field Booking');
                $mail->isHTML(true);
            } catch (Exception $e) {
                error_log("Mailer configuration error: " . $e->getMessage());
            }

            return $mail;
        }

        /**
         * Send an email with optional attachments.
         *
         * @param string      $to           Recipient email address.
         * @param string      $subject      Email subject.
         * @param string      $body         HTML body content.
         * @param string|null $altBody      Plain-text version of the email (optional).
         * @param array|null  $attachments  List of file attachments (optional). 
         *                                  Each element should be an associative array with 'path' and 'name' keys.
         *
         * @return bool True if the email was successfully sent, false otherwise.
         */
        public static function sendEmail(
            string $to, 
            string $subject,
            string $body, 
            ?string $altBody = null, 
            ?array $attachments = null
        ): bool {
            $mail = self::createMailer(); 

            try {
                $mail->addAddress($to);
                $mail->Subject = $subject;
                $mail->Body    = $body;
                $mail->AltBody = $altBody ?? strip_tags($body);

                if ($attachments && is_array($attachments)) {
                    foreach ($attachments as $file) {
                        if (file_exists($file['path'])) {
                            $mail->addAttachment($file['path'], $file['name']);
                        }
                    }
                }

                $mail->send();
                return true;
            } catch (Exception $e) {
                error_log("Email sending failed: " . $e->getMessage());
                return false;
            }
        }

        /**
         * Send a welcome email to a newly registered user.
         *
         * @param string $to   Recipient email address.
         * @param string $name Recipient's full name.
         *
         * @return bool True if the email was successfully sent, false otherwise.
         */
        public static function sendWelcomeEmail($to, $name): bool {
            try {
                $mail = self::createMailer(); 
                $mail->clearAddresses();
                $mail->addAddress($to, $name);
                $mail->isHTML(true);
                $mail->Subject = "Bienvenue sur " . SITE_NAME . " !";
                $mail->Body    = self::getWelcomeEmailTemplate($name);
                $mail->AltBody = self::getWelcomeEmailTemplate($name);
                $mail->send();
                return true;
            } catch (Exception $e) {
                error_log("Erreur PHPMailer: " . $mail->ErrorInfo);
                return false;
            }
        }

        /**
         * Send an account verification email to a user.
         *
         * @param string $to                Recipient email address.
         * @param string $name              Recipient's full name.
         * @param string $verification_code Verification code for the user.
         * @param int    $expiry            Number of minutes before the code expires.
         *
         * @return bool True if the email was successfully sent, false otherwise.
         */
        public static function sendVerificationEmail(string $to, string $name, string $verification_code, int $expiry): bool {
            try {
                $mail = self::createMailer();
                $mail->clearAddresses();
                $mail->addAddress($to, $name);
                $mail->isHTML(true);
                $mail->Subject = "Vérification de votre compte " . SITE_NAME;
                $mail->Body    = self::getVerificationEmailTemplate($name, $verification_code, $expiry);
                $mail->AltBody = self::getVerificationEmailTemplate($name, $verification_code, $expiry);
                $mail->send();
                return true;
            } catch (Exception $e) {
                error_log("Erreur PHPMailer: " . $mail->ErrorInfo);
                return false;
            }
        }


        ################################################################
        #                    Cookie Utility Functions                  #
        ################################################################

        /**
         * Set a cookie safely (for JWT or other secure data)
         *
         * @param string $name     Cookie name
         * @param string $value    Cookie value (e.g., JWT token)
         * @param int $expiry      Expiration in seconds (default: 1 hour)
         * @param string $path     Path scope (default: '/')
         * @param string $domain   Domain (optional)
         * @param bool $secure     Send only over HTTPS
         * @param bool $httpOnly   Accessible only by HTTP (not JS)
         */
        public static function setCookieSafe(
            string $name,
            string $value,
            int $expiry = 3600,
            string $path = '/',
            string $domain = '',
            bool $secure = true,
            bool $httpOnly = true
        ): void {
            setcookie(
                $name,
                $value,
                [
                    'expires' => time() + $expiry,
                    'path' => $path,
                    'domain' => $domain ?: $_SERVER['SERVER_NAME'],
                    'secure' => $secure,
                    'httponly' => $httpOnly,
                    'samesite' => 'Strict' // or 'Lax' if you have cross-site subdomains
                ]
            );
        }


        /**
         * Get a cookie value
         *
         * @param string $name
         * @return string|false  Cookie value or false if not found
         */
        public static function getCookieValue(string $name)
        {
            return $_COOKIE[$name] ?? false;
        }


        /**
         * Delete a cookie (force expire)
         *
         * @param string $name
         * @param string $path
         * @param string $domain
         */
        public static function deleteCookie(string $name, string $path = '/', string $domain = ''): void
        {
            setcookie(
                $name,
                '',
                [
                    'expires' => time() - 3600,
                    'path' => $path,
                    'domain' => $domain ?: $_SERVER['SERVER_NAME'],
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]
            );
        }



        ################################################################
        #                      RECAPTCHA SPECIFIC                      #
        ################################################################


        /**
         * Verify Google reCAPTCHA v2 or v3 response.
         *
         * @param string $recaptchaResponse The value of 'g-recaptcha-response' from the form
         * @param string $secretKey Your secret key for reCAPTCHA
         * @param string $verifyUrl The verification URL (default: Google's)
         * @return void Throws HTTP error and exits if verification fails
         */

        public static function verifyRecaptcha(
            string $recaptchaResponse,
            string $secretKey,
            string $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify'
        ): void {

           
           
            if (empty($recaptchaResponse)) {
                http_response_code(400);
                echo 'Captcha not completed.';
                exit;
            }

            
            $data = [
                'secret' => $secretKey,
                'response' => $recaptchaResponse,
                'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
            ];

           
            $ch = curl_init($verifyUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            
            $resp = json_decode($response, true);

           
            if (!isset($resp['success']) || $resp['success'] !== true) {
                http_response_code(403);
                echo 'Captcha verification failed.';
                exit;
            }

            
        }



        ################################################################
        #                      FACTURES                                #
        ################################################################


        public static function generateInvoicePDF($reservation, $options, $total_options, $total_general) {
            // nouvelel instance tcpdf
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            
            // juste un prototype, mn baed nstyliw facture

            $pdf->SetCreator('FootBooking');
            $pdf->SetAuthor('FootBooking');
            $pdf->SetTitle('Facture Réservation ' . $reservation['id_reservation']);
            $pdf->SetSubject('Facture');
            
            
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // Ajouter une page
            $pdf->AddPage();
            
            // contenu
            $html = self::generateInvoiceHTML($reservation, $options, $total_options, $total_general, true);
            
            // html contenu
            $pdf->writeHTML($html, true, false, true, false, '');
            
            // telecharger
            $pdf->Output('facture_reservation_' . $reservation['id_reservation'] . '.pdf', 'D');
        }


        public static function generateInvoiceHTML($reservation, $options, $total_options, $total_general, $forPDF = false) {
            $date_reservation = date('d/m/Y', strtotime($reservation['date_reservation']));
            $heure_debut = date('H:i', strtotime($reservation['heure_debut']));
            $heure_fin = date('H:i', strtotime($reservation['heure_fin']));
            
            if ($forPDF) {
                $styles = "
                    <style>
                        body { font-family: helvetica; font-size: 12px; }
                        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #16a34a; padding-bottom: 10px; }
                        .invoice-title { color: #16a34a; font-size: 24px; font-weight: bold; }
                        .section { margin-bottom: 15px; }
                        .section-title { background-color: #f0f0f0; padding: 8px; font-weight: bold; border-left: 4px solid #16a34a; }
                        .info-grid { display: table; width: 100%; }
                        .info-row { display: table-row; }
                        .info-label { display: table-cell; font-weight: bold; width: 30%; padding: 4px; }
                        .info-value { display: table-cell; padding: 4px; }
                        .table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                        .table th { background-color: #16a34a; color: white; padding: 8px; text-align: left; }
                        .table td { padding: 8px; border-bottom: 1px solid #ddd; }
                        .total-section { background-color: #f9f9f9; padding: 10px; margin-top: 20px; border: 1px solid #ddd; }
                        .total-row { display: flex; justify-content: space-between; margin: 5px 0; }
                        .grand-total { font-size: 16px; font-weight: bold; color: #16a34a; border-top: 2px solid #16a34a; padding-top: 5px; }
                        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #666; }
                    </style>
                ";
            } else {
                $styles = "";
            }
            
            $html = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <title>Facture Réservation {$reservation['id_reservation']}</title>
                    $styles
                </head>
                <body>
                    <div class='header'>
                        <h1 class='invoice-title'>FOOTBOOKING</h1>
                        <h2>FACTURE</h2>
                        <p>Réservation #{$reservation['id_reservation']}</p>
                    </div>
                    
                    <div class='section'>
                        <div class='section-title'>Informations de la réservation</div>
                        <div class='info-grid'>
                            <div class='info-row'>
                                <div class='info-label'>Numéro de réservation:</div>
                                <div class='info-value'>#{$reservation['id_reservation']}</div>
                            </div>
                            <div class='info-row'>
                                <div class='info-label'>Date de réservation:</div>
                                <div class='info-value'>$date_reservation</div>
                            </div>
                            <div class='info-row'>
                                <div class='info-label'>Créneau horaire:</div>
                                <div class='info-value'>$heure_debut - $heure_fin</div>
                            </div>
                            <div class='info-row'>
                                <div class='info-label'>Statut:</div>
                                <div class='info-value'>{$reservation['statut']}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='section'>
                        <div class='section-title'>Informations du terrain</div>
                        <div class='info-grid'>
                            <div class='info-row'>
                                <div class='info-label'>Terrain:</div>
                                <div class='info-value'>{$reservation['nom_terrain']}</div>
                            </div>
                            <div class='info-row'>
                                <div class='info-label'>Adresse:</div>
                                <div class='info-value'>{$reservation['adresse']}, {$reservation['ville']}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='section'>
                        <div class='section-title'>Informations client</div>
                        <div class='info-grid'>
                            <div class='info-row'>
                                <div class='info-label'>Nom complet:</div>
                                <div class='info-value'>{$reservation['prenom']} {$reservation['nom']}</div>
                            </div>
                            <div class='info-row'>
                                <div class='info-label'>Email:</div>
                                <div class='info-value'>{$reservation['email']}</div>
                            </div>
                            <div class='info-row'>
                                <div class='info-label'>Téléphone:</div>
                                <div class='info-value'>{$reservation['telephone']}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='section'>
                        <div class='section-title'>Détails de la facturation</div>
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Location du terrain {$reservation['nom_terrain']}</td>
                                    <td>" . number_format($reservation['prix_heure'], 2) . " DH</td>
                                    <td>1 heure</td>
                                    <td>" . number_format($reservation['prix_heure'], 2) . " DH</td>
                                </tr>";
            
            // Ajouter les options
            foreach ($options as $option) {
                $html .= "
                                <tr>
                                    <td>{$option['nom_option']}</td>
                                    <td>" . number_format($option['prix'], 2) . " DH</td>
                                    <td>1</td>
                                    <td>" . number_format($option['prix'], 2) . " DH</td>
                                </tr>";
            }
            
            $html .= "
                            </tbody>
                        </table>
                        
                        <div class='total-section'>
                            <div class='total-row'>
                                <span>Sous-total terrain:</span>
                                <span>" . number_format($reservation['prix_heure'], 2) . " DH</span>
                            </div>";
            
            if ($total_options > 0) {
                $html .= "
                            <div class='total-row'>
                                <span>Options supplémentaires:</span>
                                <span>" . number_format($total_options, 2) . " DH</span>
                            </div>";
            }
            
            $html .= "
                            <div class='total-row grand-total'>
                                <span>TOTAL:</span>
                                <span>" . number_format($total_general, 2) . " DH</span>
                            </div>
                        </div>
                    </div>";
            
            if (!$forPDF) {
                $html .= "
                    <div class='section'>
                        <div class='section-title'>Commentaires</div>
                        <p>" . nl2br(htmlspecialchars($reservation['commentaires'] ?? 'Aucun commentaire')) . "</p>
                    </div>";
            }
            
            $html .= "
                    <div class='footer'>
                        <p>Merci pour votre réservation !</p>
                        <p>FootBooking - Votre partenaire de football préféré</p>
                        <p>Facture générée le " . date('d/m/Y à H:i') . "</p>
                    </div>
                </body>
                </html>
            ";
            
            return $html;
        }





        
    }


    


    

?>
