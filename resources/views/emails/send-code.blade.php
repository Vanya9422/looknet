<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1, width=device-width" />
        <style>
            body {
                margin: 0;
                line-height: normal;
                width: 100%;
                position: relative;
            }

            .rectangle-parent {
                position: relative;
                background-color: #f2f5f8;
                width: calc(100% - 64px);
                height: auto;
                overflow: hidden;
                font-size: 14px;
                color: #6a7c94;
                font-family: Helvetica;
                max-width: 640px;
                margin: auto;
                padding: 49px 32px;
            }

            .header,
            .main,
            .footer {
                padding: 32px;
            }

            .header {
                padding-top: 0;
            }

            .main {
                background-color: #ffffff;
            }

            .main .title {
                font-size: 32.65px;
                letter-spacing: -0.06em;
                line-height: 100%;
                font-weight: 600;
                text-align: center;
                display: flex;
                flex-wrap: wrap;
                padding-bottom: 18px;
            }

            .main .title .hello,
            .main .title img {
                padding-right: 10px;
            }

            .main .title .hello {
                color: #062439;
            }

            .main .title .username {
                color: #556dee;
            }

            .main .text,
            .main .text2 {
                font-size: 16px;
                line-height: 24px;
                color: #333;
            }

            .main .text {
                padding-bottom: 36px;
            }

            .main .code-title {
                font-size: 18px;
                line-height: 100%;
                font-weight: 500;
                padding-bottom: 26px;
                text-align: center;
            }

            .main .code {
                font-size: 41.65px;
                line-height: 100%;
                font-family: Helvetica;
                color: #556dee;
                background-color: #f5f7fa;
                padding: 15px;
                text-align: center;
            }

            .main .text2 {
                padding-top: 41px;
                padding-bottom: 12px;
            }
        </style>
    </head>
    <body
        style="margin: 0; line-height: normal; width: 100%; position: relative"
    >
        <div
            class="rectangle-parent"
            style="
                position: relative;
                background-color: #f2f5f8;
                width: calc(100% - 64px);
                height: auto;
                overflow: hidden;
                font-size: 14px;
                color: #6a7c94;
                font-family: Helvetica;
                max-width: 640px;
                margin: auto;
                padding: 49px 32px;
            "
        >
            @include('emails.includes.header')

            <div class="main" style="padding: 32px; background-color: #ffffff">
                <div
                    class="title"
                    style="
                        font-size: 32.65px;
                        letter-spacing: -0.06em;
                        line-height: 100%;
                        font-weight: 600;
                        text-align: center;
                        display: flex;
                        flex-wrap: wrap;
                        padding-bottom: 18px;
                    "
                >
                    <div
                        class="hello"
                        style="padding-right: 10px; color: #062439"
                    >
                        {{ __("mails.HELLO", [], $local) }}
                    </div>
                    <img
                        style="padding-right: 10px"
                        width="26"
                        height="26"
                        src="{{asset('img/mails/hello.png')}}"
                    />
                    <div class="username" style="color: #556dee">
                        {{$user->full_name}}
                    </div>
                </div>
                <div
                    class="text"
                    style="
                        font-size: 16px;
                        line-height: 24px;
                        color: #333;
                        padding-bottom: 36px;
                    "
                >
                    {{ $content_text }}
                </div>
                <div
                    class="code-title"
                    style="
                        font-size: 18px;
                        line-height: 100%;
                        font-weight: 500;
                        padding-bottom: 26px;
                        text-align: center;
                    "
                >
                    {{ __("mails.ConfirmCode", [], $local) }}
                </div>
                <div
                    class="code"
                    style="
                        font-size: 41.65px;
                        line-height: 100%;
                        font-family: Helvetica;
                        color: #556dee;
                        background-color: #f5f7fa;
                        padding: 15px;
                        text-align: center;
                    "
                >
                    {{ $code }}
                </div>
                <div
                    class="text2"
                    style="
                        font-size: 16px;
                        line-height: 24px;
                        color: #333;
                        padding-top: 41px;
                        padding-bottom: 12px;
                    "
                >
                    {{ __("mails.Ignore", [], $local) }}
                </div>
            </div>

            @include('emails.includes.footer')
        </div>
    </body>
</html>
