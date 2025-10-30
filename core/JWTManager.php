<?php

/**
 * Class JWTManager
 * 
 * Utility class for creating, validating, and decoding JSON Web Tokens (JWT).
 * Provides secure token-based authentication without sessions.
 * 
 * @package Core
 * @author  Aya Essebaiy
 * @version 1.0
 */
class JWTManager
{
    /**
     * Creates a JWT token with user data.
     * 
     * @param array $userData User information to encode in the token.
     * @return string The generated JWT token.
     */
    public static function createToken(array $userData): string
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $issuedAt = time();
        $expiryTime = $issuedAt + (JWT_EXPIRY_MINUTES * 60);

        $payload = [
            'iat' => $issuedAt,                    // Issued at
            'exp' => $expiryTime,                  // Expiry time
            'user_id' => $userData['id_utilisateur'],
            'email' => $userData['email'],
            'nom' => $userData['nom'],
            'prenom' => $userData['prenom'],
            'role' => $userData['role']
        ];

        // Encode Header
        $headerEncoded = self::base64UrlEncode(json_encode($header));

        // Encode Payload
        $payloadEncoded = self::base64UrlEncode(json_encode($payload));

        // Create Signature
        $signature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, JWT_SECRET_KEY, true);
        $signatureEncoded = self::base64UrlEncode($signature);

        // Create JWT
        return $headerEncoded . "." . $payloadEncoded . "." . $signatureEncoded;
    }

    /**
     * Validates and decodes a JWT token.
     * 
     * @param string $token The JWT token to validate.
     * @return array|null Decoded payload if valid, null otherwise.
     */
    public static function validateToken(string $token): ?array
    {
        if (empty($token)) {
            return null;
        }

        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return null;
        }

        [$headerEncoded, $payloadEncoded, $signatureProvided] = $parts;

        // Verify signature
        $signature = hash_hmac('sha256', $headerEncoded . "." . $payloadEncoded, JWT_SECRET_KEY, true);
        $signatureEncoded = self::base64UrlEncode($signature);

        if ($signatureEncoded !== $signatureProvided) {
            return null; // Invalid signature
        }

        // Decode payload
        $payload = json_decode(self::base64UrlDecode($payloadEncoded), true);

        // Check expiry
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return null; // Token expired
        }

        return $payload;
    }

    /**
     * Extracts token from Authorization header.
     * Supports "Bearer <token>" format.
     * 
     * @return string|null The token if found, null otherwise.
     */
    public static function getTokenFromHeader(): ?string
    {
        $headers = getallheaders();
        
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            
            // Check for "Bearer <token>" format
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                return $matches[1];
            }
            
            return $authHeader;
        }

        return null;
    }

    /**
     * Gets the current authenticated user from token.
     * 
     * @return array|null User data if authenticated, null otherwise.
     */
    public static function getCurrentUser(): ?array
    {
        $token = self::getTokenFromHeader();
        
        if (!$token) {
            return null;
        }

        return self::validateToken($token);
    }

    /**
     * Checks if the current request is authenticated.
     * 
     * @return bool True if authenticated, false otherwise.
     */
    public static function isAuthenticated(): bool
    {
        return self::getCurrentUser() !== null;
    }

    /**
     * Checks if the current user has a specific role.
     * 
     * @param string $role The role to check.
     * @return bool True if user has the role, false otherwise.
     */
    public static function hasRole(string $role): bool
    {
        $user = self::getCurrentUser();
        
        return $user && isset($user['role']) && $user['role'] === $role;
    }

    /**
     * Base64 URL-safe encoding.
     * 
     * @param string $data Data to encode.
     * @return string Encoded string.
     */
    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base64 URL-safe decoding.
     * 
     * @param string $data Data to decode.
     * @return string Decoded string.
     */
    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Refreshes a token (creates a new one with updated expiry).
     * 
     * @param string $token The current token.
     * @return string|null New token if valid, null otherwise.
     */
    public static function refreshToken(string $token): ?string
    {
        $payload = self::validateToken($token);
        
        if (!$payload) {
            return null;
        }

        // Create new token with same user data
        return self::createToken([
            'id_utilisateur' => $payload['user_id'],
            'email' => $payload['email'],
            'nom' => $payload['nom'],
            'prenom' => $payload['prenom'],
            'role' => $payload['role']
        ]);
    }
}

?>