<?php


class Template {

    public static string $WELCOME_EMAIL_TEMPLATE = "
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Bienvenue</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6; 
                    color: #333; 
                    margin: 0; 
                    padding: 0; 
                    background-color: #f9fafb;
                }
                .container { 
                    max-width: 600px; 
                    margin: 0 auto; 
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                .header { 
                    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); 
                    color: white; 
                    padding: 40px 30px; 
                    text-align: center; 
                }
                .content { 
                    padding: 40px 30px; 
                }
                .button { 
                    display: inline-block; 
                    background: #16a34a; 
                    color: white; 
                    padding: 14px 35px; 
                    text-decoration: none; 
                    border-radius: 8px; 
                    margin: 25px 0; 
                    font-weight: bold;
                    font-size: 16px;
                }
                .footer { 
                    text-align: center; 
                    margin-top: 30px; 
                    color: #6b7280; 
                    font-size: 14px;
                    padding: 20px;
                    background: #f9fafb;
                }
                .feature-list {
                    margin: 20px 0;
                }
                .feature-item {
                    padding: 10px 0;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Bienvenue sur {SITE_NAME} !</h1>
                    <p>Votre compte a été activé avec succès</p>
                </div>
                <div class='content'>
                    <p>Bonjour <strong>{name}</strong>,</p>
                    <p>Félicitations ! Votre compte a été activé avec succès. Vous pouvez maintenant profiter de tous nos services :</p>
                    
                    <div class='feature-list'>
                        <div class='feature-item'>✔ Réserver des terrains en quelques clics</div>
                        <div class='feature-item'>✔ Gérer vos réservations facilement</div>
                        <div class='feature-item'>✔ Accéder aux promotions exclusives</div>
                        <div class='feature-item'>✔ Participer à des tournois et événements</div>
                        <div class='feature-item'>✔ Consulter votre historique de réservations</div>
                    </div>

                    <a href='{SITE_URL}/auth/login.php' class='button'>Commencer à réserver</a>
                    
                    <p>Si vous avez des questions ou besoin d'aide, n'hésitez pas à nous contacter.</p>
                </div>
                <div class='footer'>
                    <p>&copy; {YEAR} {SITE_NAME} - Tous droits réservés</p>
                </div>
            </div>
        </body>
        </html>
    ";

    
    public static string $ACCOUNT_VERIFICATION_EMAIL_TEMPLATE = "
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Vérification du compte</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6; 
                    color: #333; 
                    margin: 0; 
                    padding: 0; 
                    background-color: #f9fafb;
                }
                .container { 
                    max-width: 600px; 
                    margin: 0 auto; 
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                .header { 
                    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); 
                    color: white; 
                    padding: 40px 30px; 
                    text-align: center; 
                }
                .content { 
                    padding: 40px 30px; 
                }
                .code-box {
                    background: #f3f4f6;
                    padding: 20px;
                    text-align: center;
                    font-size: 22px;
                    font-weight: bold;
                    letter-spacing: 4px;
                    border-radius: 6px;
                    color: #111827;
                    margin: 25px 0;
                }
                .footer { 
                    text-align: center; 
                    margin-top: 30px; 
                    color: #6b7280; 
                    font-size: 14px;
                    padding: 20px;
                    background: #f9fafb;
                }
                .expiry-notice {
                    background: #fef3c7;
                    padding: 12px;
                    border-radius: 6px;
                    margin: 15px 0;
                    color: #92400e;
                    font-size: 14px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Bienvenue sur {SITE_NAME} !</h1>
                    <p>Vérifiez votre compte pour commencer</p>
                </div>
                <div class='content'>
                    <p>Bonjour <strong>{name}</strong>,</p>
                    <p>Merci de vous être inscrit sur {SITE_NAME} !</p>
                    <p>Veuillez utiliser le code de vérification ci-dessous pour activer votre compte :</p>
                    
                    <div class='code-box'>{verification_code}</div>

                    <div class='expiry-notice'>
                        Ce code est valable pendant {expiry} minutes.
                    </div>
                    
                    <p>Si vous n'avez pas créé de compte sur {SITE_NAME}, ignorez simplement cet email.</p>
                </div>
                <div class='footer'>
                    <p>&copy; {YEAR} {SITE_NAME} - Tous droits réservés</p>
                    <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
                </div>
            </div>
        </body>
        </html>
    ";

    

    public static function render(string $template, array $vars = []): string {
        $vars = array_merge([
            'SITE_NAME' => SITE_NAME,
            'SITE_URL'  => SITE_URL,
            'YEAR'      => date('Y')
        ], $vars);

        foreach ($vars as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        return $template;
    }
}
