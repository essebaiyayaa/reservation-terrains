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
        function getCookieValue(string $name)
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
        function deleteCookie(string $name, string $path = '/', string $domain = ''): void
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
        
    }

?>
