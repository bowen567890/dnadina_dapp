<?php

namespace App\Services\Web3;

use Exception;
use kornrunner\Keccak;
use Elliptic\EC;

class Web3SignatureService
{
    /**
     * 验证签名
     * @param string $address 钱包地址
     * @param string $message 原始消息
     * @param string $signature 签名
     * @return bool
     */
    public function verifySignature(string $address, string $message, string $signature): bool
    {
        try {
            // 1. 验证地址格式
            if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $address)) {
                return false;
            }

            // 2. 验证签名格式
            if (!preg_match('/^0x[a-fA-F0-9]{130}$/', $signature)) {
                return false;
            }

            // 3. 本地验证签名
            $messageHash = $this->hashMessage($message);
            $signature = substr($signature, 2);
            $r = substr($signature, 0, 64);
            $s = substr($signature, 64, 64);
            $v = ord(hex2bin(substr($signature, 128, 2))) - 27;
            if ($v != 0 && $v != 1) {
                return false;
            }

            $ec = new EC('secp256k1');
            $publicKey = $ec->recoverPubKey($messageHash, [
                'r' => $r,
                's' => $s
            ], $v);

            $recoveredAddress = '0x' . substr(Keccak::hash(substr(hex2bin($publicKey->encode('hex')), 1), 256), 24);

            return strtolower($recoveredAddress) === strtolower($address);
        } catch (Exception $e) {
            // 记录错误日志
            return false;
        }
    }

    /**
     * 生成随机消息用于签名
     * @return string
     */
    public function generateMessage(): string
    {
        $timestamp = time();
        $nonce = bin2hex(random_bytes(16));
        return "Welcome to our DApp!\n\nPlease sign this message to login.\n\nNonce: {$nonce}\nTimestamp: {$timestamp}";
    }

    /**
     * 对消息进行哈希处理
     * @param string $message
     * @return string
     */
    private function hashMessage(string $message): string
    {
        $prefix = "\x19Ethereum Signed Message:\n" . strlen($message);
        return Keccak::hash($prefix . $message, 256);
    }
}
