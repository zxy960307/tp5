<?php
/**
 * Created by PhpStorm.
 * User: 41463
 * Date: 2018/4/14
 * Time: 22:08
 */
    return [
        //应用ID,您的APPID。
        'app_id' => "2016091500519549",

        //商户私钥
        'merchant_private_key' => "MIIEowIBAAKCAQEAqT4i8E50SwIncAfD36/YPkeLyWTzD+4T/aIS83hoR+cc77nSS2oKqdBcJN2ZcUnbGUxbm9RCx9fpTI0jwUSyAhI+1ja1fRANKuogN5gAMezQd2EfBCF9K7UdRS59BevswmV1jyhyn57vdBWnW57cbQ85rw+LZSNPjgKModloCnfW+citwzZRLuFaaprZpkpS8OZp4SSkrgvx2B9ZhzjfpkyYHXhn7FJoW66r2jMABwVS0IfOWRoY8co2R06/U7Cq0zjWM6X1effWi/FN9adSNK1KubmmEV3cSiX7sI6MAMV310mYjzaLVphb11hL/riW9RpwPGrNlLLxjV4cOWSQuwIDAQABAoIBAEua24bYtXDwxGdywJkD+ClSUuskMsUyCTIsHiUv6/37C903+O8Br0PPNequKCcI5Fz31JDXkQGvvaQaBRgItTDhr7qIdCkra4Z5Uu0i9StYbIR4GaAByeYloa7PJsDndTwekRbj7djK6pPEPKBcZoI94/oFirMixB5fmy2ObsEFsA/IbTVSSVF6uFlw0xCitAY4Oju2XYVP3vaFOGw7LZamPG2W/Y++R96R/J/GbKF/RGCFU83EtMOtmPQ2+ppw9xvXmzuGMRuPEsk4ImqeYJ9jlvx/ILfT+3Rztijv2H3sfhsonIcksjVNKQqJL0ggM0qe9crHKG6O6249Yd3pTrkCgYEA2kL6rT63Qs0vHaTeZr3HG4osAek5/ZHVoeaFSWHdAQMOmVe7E5jV7qI5pQmhnLMsMhgPMx7a87iXOl9aO/toSEnMCKK3wyWouwK9l5T5rG5fUPNQQ6wvdrJCAuduw/+ZSfwI3McErsMh1yK6ZoDSSxaWE4mPsJWEGWAWtagCpw8CgYEAxoFovu43a1DLKxBz4J/wWGbtU93Iu4WMaaXb7r+XFghnXxb+mAHsR85N4vCdT7Tfic8B1Tei78+y53bOuQnaLburwDi3dAaSm9XGRCE97mel8koUK+uLj/jVoovGloqaalDjpZ4x7+92/NERaDr34sIj7c8HnCfOCftO5vIZW5UCgYBXWvNln3JNpD21SaVRzacBGL4gTa5OFK5CTDcQdcC0hPq02O4hG8yT5A3aRg6O3w9TRVngBftwDHNKg5tj4m2McSzeT/HogwwTPTeQkamUH+C6T4fSbfnZMeNFskcc3xIFEICjjGwNXkAPEPE10V1ZfEK4sxf1fwIN/e1Y61OUdQKBgHomV9ZlsCSRavPtA+FNihBcBd59s2/xFh5MubGIo+gi87lH5RKU3FizUqo7dhH+MzAR+gbCXblEDcrxCPREY8WQnneCbyEuijjqqz5xVjLGBQ+7Ff6Qyyik7pE/gcxRLgQOEodveTs0F/wqipOg9bjLzmAf7Ybh15+8zSjAAWqhAoGBAImVd32f6SeFRt3IBJYRncQMEhPRaEngLp3GfrvM/fUpVtzF1tQvsmBM6WcRMismV6TyJs7GJYiYWLFtBrfZHD9Yvfc+iNW/TEyXGXT8Dc7njC7o5O1BAnwGSKIYG6bwfipXdAUAUsap9j7ThU8MZKrMycbqHGLSl+eMAaaBj5dS",

        //异步通知地址
        'notify_url' => "119.23.219.185/index.php/pay/notify",

        //同步跳转
        'return_url' => "",

        //编码格式
        'charset' => "UTF-8",

        //签名方式
        'sign_type'=>"RSA2",

        //支付宝网关
        'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqTk2wtxoTXK/8yPYsoZFgV1KVYce4Rd6lqX/wtIaskirH5NoGhNfAEPRrM8Gki1xh17ynub2pTdX/Y0ynCwezFlUpFSteG+JuH+YOlaeOiSFQCTNYCyXj+Jtfl6hmFNAmhkpAZzGbSqgax0LAvPPvIlq5njFCG6uPO6oVpCQ/3+gbjpZ+EAUIZFaneo1DURV9HT/Wt+gCLvrWtu4c+jf6z41aSTMsz9jotpX7/BpMNXZwVkGSmHy0KwMdj8ynDp0iCjUF4mR53HvlMtXWOIWSQzk9s/303nlL01zJ6qkpdthwshsHjq3ZU+YGw+DrbrK3NRPZM9N9URC+9xQg1wGewIDAQAB",

    ];
