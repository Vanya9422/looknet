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
                color: {{$advertise->status !== \App\Enums\Advertise\AdvertiseStatus::Active ? '#EB5757' : '#556dee'}};
            }
            .main .text {
                font-size: 16px;
                line-height: 24px;
                padding-bottom: 18px;
                color: #333;
            }
            .main .hr {
                border-top: 1px solid #E2E2E2;
                padding-bottom: 18px;
            }
            .main .publication {
                display: flex;
                flex-direction: row;
                width: 100%;
                position: relative;
            }
            .main .publication .img {
                width: 40%;
                padding-right: 20px;
                user-select: none;
                position: relative;
            }
            .main .publication img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 10px;
            }
            .main .publication .info {
                flex-grow: 1;
            }
            .main .publication .info-title {
                font-weight: 600;
                font-size: 15px;
                line-height: 18px;
                padding-bottom: 15px;
                font-family: Helvetica;
            }
            .main .publication .info-title a {
                transition: ease .12s;
                color: #062439 !important;
            }
            .main .publication .info-title a:hover {
                color: #556dee !important;
            }
            .main .publication .info-description {
                font-weight: 500;
                font-size: 14px;
                line-height: 14px;
                color: #6E6E73;
                padding-bottom: 15px;
                font-family: Helvetica;
            }
            .main .publication button {
                font-weight: 600;
                font-size: 14px;
                line-height: 14px;
                text-align: center;
                color: #FFFFFF;
                background-color: #556dee;
                border-radius: 30px;
                min-height: 32px;
                min-width: 166px;
                border: none;
                cursor: pointer;
                transition: ease .12s;
                font-family: Helvetica;
            }
            .main .publication button:hover {
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
            sryle="
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
                padding: 49px 32px;"
        >
            @include('emails.includes.header')

            <div class="main" style="padding: 32px; background-color: #ffffff">
                <div
                    class="title"
                    style="font-size: 32.65px;
                letter-spacing: -0.06em;
                line-height: 100%;
                font-weight: 600;
                text-align: center;
                display: flex;
                flex-wrap: wrap;
                padding-bottom: 18px;
                color: {{$advertise->status !== \App\Enums\Advertise\AdvertiseStatus::Active ? '#EB5757' : '#556dee'}};"
                >
                    {{ $title }}
                </div>
                <div
                    class="text"
                    style="
                        font-size: 16px;
                        line-height: 24px;
                        padding-bottom: 18px;
                        color: #333;
                    "
                >
                    {{ $content_text }}
                </div>
                <div
                    class="hr"
                    style="border-top: 1px solid #e2e2e2; padding-bottom: 18px"
                ></div>
                <div
                    class="publication"
                    style="
                        display: flex;
                        flex-direction: row;
                        width: 100%;
                        position: relative;
                    "
                >
                    <div
                        class="img"
                        style="
                            width: 40%;
                            padding-right: 20px;
                            user-select: none;
                            position: relative;
                        "
                    >
                        <a
                            href="{{env('app_front_web_url') . 'product/' . $advertise->slug}}"
                            style="text-decoration: none"
                        >
                            <img
                                src="{{$advertise->previewImage->getFullUrl()}}"
                                style="
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                    border-radius: 10px;
                                "
                            />
                        </a>
                    </div>
                    <div class="info" style="flex-grow: 1">
                        <div
                            class="info-title"
                            style="
                                font-weight: 600;
                                font-size: 15px;
                                line-height: 18px;
                                padding-bottom: 15px;
                                font-family: Helvetica;
                            "
                        >
                            <a
                                href="{{env('app_front_web_url') . 'product/' . $advertise->slug}}"
                                style="
                                    transition: ease 0.12s;
                                    color: #062439 !important;
                                    text-decoration: none;
                                "
                            >
                                {{$advertise->name}}
                            </a>
                        </div>
                        <div
                            class="info-description"
                            style="
                                font-weight: 500;
                                font-size: 14px;
                                line-height: 14px;
                                color: #6e6e73;
                                padding-bottom: 15px;
                                font-family: Helvetica;
                            "
                        >
                            {{\Illuminate\Support\Str::limit($advertise->description)}}
                        </div>
                        <a
                            href="{{env('app_front_web_url') . 'product/' . $advertise->slug}}"
                            style="text-decoration: none"
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
                                {{ __("mails.goTo", [], $local) }}
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            @include('emails.includes.footer')
        </div>
    </body>
</html>
