<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>修改登录密码</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            display: inline-block;
            background-color: #007bff; /* 蓝色背景 */
            color: #fff; /* 白色文字 */
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
        }
        .content {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo">RAB-COIN</div>
    </div>
    <div class="content">
        <p>尊敬的{{ $data['nickname'] }}，</p>
        <p>您的验证码是：{{ $data['code'] }}</p>
        <p>为了安全起见，验证码将在5分钟后过期。请不要将验证码分享给任何人。</p>
        <p>如果您没有发起此操作，请忽略此邮件。</p>
    </div>
    <div class="footer">
        <p>{{ $data['c_name'] }} | {{ $data['c_address'] }} | {{ $data['zip_code'] }}</p>
        <p>此邮件由我们的平台自动生成。如果您认为这是垃圾邮件，请立即向<a href="mailto:{{ $data['service_email'] }}">{{ $data['service_email'] }}</a>举报。</p>
        <p>回复此邮件无法得到回复。如需更多信息，您可以联系<a href="mailto:{{ $data['service_email'] }}">{{ $data['service_email'] }}</a>。</p>
        <p>{{ $data['time'] }}</p>
    </div>
</div>
</body>
</html>
