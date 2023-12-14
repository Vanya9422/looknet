<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1, width=device-width" />
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
                align-items: center;
                display: flex;
                flex-wrap: wrap;
                padding-bottom: 18px;
                color: #062439;
                position: relative;
            }
            .main .title .envelope {
                padding-right: 10px;
            }
            .main .title .check-mark {
                position: absolute;
                left: 22px;
                top: 16px;
            }
            .main .text {
                font-size: 16px;
                line-height: 24px;
                padding-bottom: 35px;
                color: #333;
            }
            .main .text a {
                font-weight: 700;
                transition: ease 0.12s;
                color: #556dee !important;
            }
            .main .text a:hover {
                color: #4257c7 !important;
            }
            .main button {
                font-weight: 600;
                font-size: 14px;
                line-height: 14px;
                text-align: center;
                color: #ffffff;
                background-color: #556dee;
                border-radius: 30px;
                min-height: 32px;
                min-width: 166px;
                border: none;
                cursor: pointer;
                transition: ease 0.12s;
                font-family: Helvetica;
            }
            .main button:hover {
                background-color: #4257c7;
            }
            a {
                text-decoration: none;
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
                        align-items: center;
                        display: flex;
                        flex-wrap: wrap;
                        padding-bottom: 18px;
                        color: #062439;
                        position: relative;
                    "
                >
                    <img
                        class="envelope"
                        style="padding-right: 10px"
                        width="35"
                        height="35"
                        src="{{asset('img/mails/mail.png')}}"
                    />
                    <img
                        class="check-mark"
                        style="position: absolute; left: 22px; top: 16px"
                        width="10"
                        height="10"
                        src="{{asset('img/mails/ok.png')}}"
                    />
                    {{ __("mails.SupportNewMessageTitle", [], $local) }}
                </div>
                <div
                    class="text"
                    style="
                        font-size: 16px;
                        line-height: 24px;
                        padding-bottom: 35px;
                        color: #333;
                    "
                >
                    {{ __("mails.SupportNewMessageText", [], $local) }}
                    <a
                        style="
                            font-weight: 700;
                            text-decoration: none;
                            transition: ease 0.12s;
                            color: #556dee !important;
                        "
                        href="{{env('app_front_web_url') . '/chat?tab=support'}}"
                    >
                        {{$ticket->supportTheme->title}}
                    </a>
                </div>
                <a
                    style="text-decoration: none"
                    href="https://looknet.com/chat?tab=support"
                >
                    <button
                        style="
                            font-weight: 600;
                            font-size: 14px;
                            line-height: 14px;
                            text-align: center;
                            color: #ffffff;
                            background-color: #556dee;
                            border-radius: 30px;
                            min-height: 32px;
                            min-width: 166px;
                            border: none;
                            cursor: pointer;
                            transition: ease 0.12s;
                            font-family: Helvetica;
                        "
                    >
                        {{ __("mails.GoToChat", [], $local) }}
                    </button>
                </a>
            </div>

            @include('emails.includes.footer')
        </div>
    </body>
</html>
