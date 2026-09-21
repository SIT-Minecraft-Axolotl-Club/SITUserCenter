import * as oidc from 'oidc-provider';
import { OIDCAdapter } from './oidc-adapter';
import { EXTENDED_PRISMA_SERVICE, ExtendedPrismaClient } from './extended-prisma-client';
import { JWK } from 'jose';
import { getDateWithTimezoneOffset } from './helper';
import { ConfigService } from '@nestjs/config';
import { UserInfo, YggCClaims, YggCScopes, YggdrasilProfile } from './blessing.types';
import { CodeIdToUUID, PassportAccessToken, Player, UUID } from '@prisma/client';
import { Inject, Injectable } from '@nestjs/common';
import { CustomPrismaService } from 'nestjs-prisma';

export const BS_RESOURCE_INDICATOR: string = "https://github.com/bs-community/blessing-skin-server";
export const ACCESS_TOKEN_NAME: string = "Yggdrasil Connect";

@Injectable()
export class OIDCProviderService {
    // 填写皮肤站站点名和站点图标 Url
    private readonly siteName = 'SIT-Minecraft';
    private readonly favicon = 'https://skin.sitmc.club/app/3.ico';
    
    generateHtml(title: string, content: string) {
        return `<!DOCTYPE html>
            <html lang="zh-CN">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://www.bootcdn.cn/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="/app/style.7eb5d06.css" rel="stylesheet" crossorigin="anonymous">

    <link rel="stylesheet"
          href="https://unpkg.com/@fortawesome/fontawesome-free@5.15.4/css/all.min.css"
          crossorigin>


    <link rel="shortcut icon" href="https://skin.sitmc.club/app/3.ico">
                    <title>${title} - ${this.siteName}</title>
                        <style>

@font-face {

    font-family:'Minecraft';

    src:url('https://cdn.jsdelivr.net/gh/South-Paw/typeface-minecraft@master/public/MinecraftRegular-Bmg3.woff2')
    format('woff2');

}


/* ==============================
   Eduroam 登录页 Minecraft主题
============================== */


body.login-page {


    background:#1a1a1a!important;

}


/* 登录整体 */

.login-box {


    width:360px;

}



/* Logo */

.login-logo,
.login-logo a {


    font-family:'Minecraft',sans-serif!important;

    color:#fff!important;

    text-shadow:
    3px 3px 0 #000!important;

}



.login-logo a:hover {


    text-decoration:none;

}



/* 卡片 */

.login-box .card {


    background:#2b2b2b!important;

    border:2px solid #000!important;

    border-radius:0!important;


    box-shadow:
    4px 4px 0 rgba(0,0,0,.6)!important;


}



/* 内容 */

.login-card-body {


    background:transparent!important;


}



/* 提示文字 */


.login-box-msg {


    color:#fff!important;

    font-family:'Minecraft',sans-serif;

    text-shadow:
    1px 1px #000;


}



/* 错误提示 */

.alert {


    border-radius:0!important;


}



.alert-danger {


    background:#661111!important;

    border:2px solid #aa3333!important;

    color:white!important;


}



.alert-success {


    background:#164d16!important;

    border:2px solid #3c8527!important;

    color:white!important;


}




/* 输入框 */


.login-box .form-control {


    background:
    rgba(255,255,255,.08)!important;


    border:

    1px solid rgba(255,255,255,.25)!important;


    color:white!important;


    border-radius:0!important;


}



.login-box .form-control::placeholder {


    color:#aaa!important;


}



.login-box .form-control:focus {


    background:
    rgba(255,255,255,.15)!important;


    border-color:#3c8527!important;


    box-shadow:
    0 0 0 .2rem rgba(60,133,39,.25)!important;


}




/* 输入框图标 */


.login-box .input-group-text {


    background:
    rgba(255,255,255,.08)!important;


    border:

    1px solid rgba(255,255,255,.25)!important;


    color:#ccc!important;


    border-radius:0!important;


}




/* 所有按钮 */

.login-box .btn {


    border-radius:0!important;

    font-family:'Minecraft',sans-serif!important;


}



/* 登录按钮 */


.login-box .btn-primary {


    background:#3c8527!important;


    border:

    2px solid #1e4513!important;


    color:white!important;


    box-shadow:


    inset -2px -4px rgba(0,0,0,.25),


    inset 2px 2px rgba(255,255,255,.35)!important;


}



.login-box .btn-primary:hover {


    background:#4aa330!important;


    transform:scale(1.02);


}



/* 其它登录按钮 */


.login-box a.bg-light {


    background:#222!important;


    border:

    2px solid #555!important;


    color:white!important;


}



.login-box a.bg-light:hover {


    background:#333!important;


}



/* 提示框 */


.login-box .callout {


    background:#222!important;


    border-left:

    5px solid #3c8527!important;


    color:#ccc!important;


    padding:15px;


}



.login-box .callout a {


    color:#3c8527!important;


}



.login-box .callout a:hover {


    color:#4aa330!important;


}



/* 图标 */

.login-box i {


    color:#ccc;


}



</style>

                </head>
                <body class="hold-transition login-page">
                    <div class="login-box">
                        <div class="login-logo">
                            <a href="${this.siteUrl}">${this.siteName}</a>
                        </div>
                        <div class="card">
                            <div class="card-body login-card-body">
                                ${content}
                            </div>
                        </div>
                    </div>
                </body>
            </html>`
    }
    
    async successSource(ctx: oidc.KoaContextWithOIDC) {
        const content = `
            <div class="text-center py-5">
                <i class="far fa-check-circle text-success fa-5x mb-4" aria-hidden="true"></i>
                <h5 class="text-success mb-0">登录成功</h5>
            </div>`;
        ctx.body = this.generateHtml('登录成功', content);
    }
    
    async userCodeConfirmSource(ctx: oidc.KoaContextWithOIDC, form: String, _client: any, _deviceInfo: any, userCode: String) {
        const content = `
            <p class="login-box-msg">登录至 ${ctx.oidc.client?.clientName || ctx.oidc.client?.clientId}</p>
            <main>
                <div class="alert alert-info">请确认以下授权码与您的应用中显示的授权码相符。</div>
                    <div class="mb-3 text-center" style="font-size: 1.6em; font-weight: bold; font-family: Minecraft;">${userCode}</div>
                <div class="alert alert-warning">
                    如果您没有发起此操作，或者该授权码与您的应用中显示的授权码不匹配，请关闭此窗口或点击取消。
                </div>
                ${form}
                <button class="btn btn-success btn-block" type="submit" form="op.deviceConfirmForm">继续</button>
                <button class="btn btn-default btn-block" type="submit" form="op.deviceConfirmForm" value="yes" name="abort">取消</button>
            </main>`;
        ctx.body = this.generateHtml('授权', content);
    }
    
    async userCodeInputSource(ctx: oidc.KoaContextWithOIDC, form: String, out: any, err: any) {
        let msg: string;
        if (err && (err.userCode || err.name === 'NoCodeError')) msg = '您输入的代码不正确，请重试';
        else if (err && err.name === 'AbortedError') msg = '登录请求被中断：' + JSON.stringify(out);
        else if (err) msg = '处理请求时发生错误：' + JSON.stringify(out);
        else msg = '请输入您设备上显示的代码';
    
        const content = `
            <p class="login-box-msg">授予应用访问权限</p>
            <main>
                <div class="alert alert-danger">${msg}</div>
                <div class="form-group">${form}</div>
                <div class="alert alert-warning">
                    请勿输入来自你不信任的来源的授权码，以免造成个人隐私泄露和账号安全问题。
                </div>
                <button class="btn btn-success btn-block" type="submit" form="op.deviceInputForm">继续</button>
            </main>
            <script>
                input = document.getElementsByName('user_code')[0];
                input.placeholder = '输入应用中显示的授权码';
                input.classList.add('form-control');
            </script>`;
        ctx.body = this.generateHtml('授权', content);
    }
    
    readonly provider: oidc.Provider;
    readonly siteUrl: string;
    readonly session: oidc.Session;

    constructor(
        private readonly config: ConfigService,
        @Inject(EXTENDED_PRISMA_SERVICE) private readonly prisma: CustomPrismaService<ExtendedPrismaClient>,
        @Inject("JWK") private readonly jwk: JWK,
    ) {
        const siteUrl: string = this.config.get<string>("BS_SITE_URL")!;
        const issuer: string = this.config.get<string>("ISSUER")!;
        const tokenExpiresIn1: number = this.config.get<number>("TOKEN_EXPIRES_IN_1")!;
        const tokenExpiresIn2: number = this.config.get<number>("TOKEN_EXPIRES_IN_2")!;
        const deviceCodeExpiresIn: number = this.config.get<number>("DEVICE_CODE_EXPIRES_IN")!;
        const grantExpiresIn: number = this.config.get<number>("GRANT_EXPIRES_IN")!;
        const sharedClientId: string | undefined = this.config.get<string>("SHARED_CLIENT_ID");

        const basePolicy = oidc.interactionPolicy.base();
        const loginPrompt = basePolicy.get('login')!;
        const consentPrompt = basePolicy.get('consent')!;
        const grantPrompt = new oidc.interactionPolicy.Prompt({ name: 'grant', requestable: true }, new oidc.interactionPolicy.Check('grant', 'invalid grant', (ctx) => {
            const oidcContext = ctx.oidc;
            if (!oidcContext.entities.Grant) {
                return oidc.interactionPolicy.Check.REQUEST_PROMPT;
            }
            return oidc.interactionPolicy.Check.NO_NEED_TO_PROMPT;
        }));

        const provider = new oidc.Provider(issuer, {
            adapter: OIDCAdapter.getAdapterFactory(this.prisma.client, this.config),
            jwks: {
                keys: [this.jwk]
            },
            clientAuthMethods: [
                'client_secret_post',
                'none'
            ],
            responseTypes: ['code', 'id_token', 'code id_token'],
            claims: {
                [YggCScopes.PROFILE]: [YggCClaims.NICKNAME, YggCClaims.PICTURE],
                [YggCScopes.EMAIL]: [YggCClaims.EMAIL, YggCClaims.EMAIL_VERIFIED],
                [YggCScopes.PROFILE_SELECT]: [YggCClaims.SELECTED_PROFILE],
                [YggCScopes.PROFILE_READ]: [YggCClaims.AVAILABLE_PROFILES]
            },
            scopes: [
                YggCScopes.EMAIL,
                YggCScopes.PROFILE,
                YggCScopes.PROFILE_SELECT,
                YggCScopes.PROFILE_READ,
                YggCScopes.SERVER_JOIN,
                'offline_access',
                'openid'
            ],
            async loadExistingGrant(ctx: oidc.KoaContextWithOIDC) {
                const grantId = ctx.oidc.result?.consent?.grantId;
                if (grantId) {
                    return ctx.oidc.provider.Grant.find(grantId);
                }
                return undefined;
            },
            findAccount: this.findAccount.bind(this),
            conformIdTokenClaims: false,
            features: {
                deviceFlow: {
                    enabled: true,
                    successSource: this.successSource.bind(this),
                    userCodeConfirmSource: this.userCodeConfirmSource.bind(this),
                    userCodeInputSource: this.userCodeInputSource.bind(this)
                },
                dPoP: {
                    enabled: false
                },
                devInteractions: { enabled: false },
                resourceIndicators: {
                    enabled: true,
                    defaultResource(ctx: oidc.KoaContextWithOIDC, client: oidc.Client, oneOf: string[] | undefined) {
                        return BS_RESOURCE_INDICATOR;
                    },
                    getResourceServerInfo(ctx: oidc.KoaContextWithOIDC, resourceIndicator: string, client: oidc.Client) {
                        return {
                            scope: Array.from(ctx.oidc.requestParamScopes).join(' '),
                            audience: BS_RESOURCE_INDICATOR,
                            accessTokenFormat: 'jwt',
                            jwt: {
                                sign: { alg: 'RS256' },
                            },
                        };
                    },
                    useGrantedResource(ctx: oidc.KoaContextWithOIDC, model) {
                        return true;
                    },
                },
                pushedAuthorizationRequests: {
                    enabled: false
                },
                rpInitiatedLogout: {
                    enabled: false
                }
            },
            formats: {
                customizers: {
                    async jwt(ctx, token, jwt) {
                        jwt.payload.aud = ctx.oidc.client!.clientId;
                        return jwt;
                    },
                }
            },
            interactions: {
                policy: [
                    loginPrompt,
                    grantPrompt,
                    consentPrompt
                ],
                url(ctx, interaction) {
                    const prompt = interaction.prompt;
                    return `/api/janus/interaction/${interaction.uid}`;
                },
            },
            async extraTokenClaims(ctx: oidc.KoaContextWithOIDC, token: oidc.AccessToken) {
                const oidcContext = ctx.oidc;
                const claims: oidc.AccountClaims | undefined = await oidcContext.account?.claims('id_token', token.scope!, {}, []);
                const selectedProfile = claims?.selectedProfile as YggdrasilProfile | undefined;
                return {
                    selectedProfile: selectedProfile?.id,
                    scopes: Array.from(oidcContext.entities.RefreshToken?.scopes ?? token.scopes)
                };
            },
            expiresWithSession(ctx: oidc.KoaContextWithOIDC, token): boolean {
                return false;
            },
            ttl: {
                AccessToken: tokenExpiresIn1,
                AuthorizationCode: 10 * 60,
                DeviceCode: deviceCodeExpiresIn,
                Grant: grantExpiresIn,
                IdToken: tokenExpiresIn1,
                RefreshToken: tokenExpiresIn2,
                Session: 15 * 60,
                Interaction: 15 * 60,
            },
            rotateRefreshToken: true,
            routes: {
                userinfo: '/userinfo'
            },
            clientDefaults: {
                application_type: 'native',
                response_types: ['code', 'id_token', 'code id_token'],
                grant_types: ['authorization_code', 'implicit', 'refresh_token', 'urn:ietf:params:oauth:grant-type:device_code'],
                token_endpoint_auth_method: "client_secret_post"
            },
            discovery: {
                shared_client_id: sharedClientId?.length ? sharedClientId : undefined,
            },
        });

        provider.proxy = true;

        provider.on('server_error', (ctx, error) => {
            console.log(error);
            console.log(error.stack);
        });

        /* 
            如果签发的 Access Token 是 JWT，oidc-provider 不会把 Access Token 写到数据库里
            所以要手动把 Access Token 保存到 Laravel Passport 的数据表中
        */
        provider.on('access_token.issued', async (token: oidc.AccessToken) => {

            const date: Date = getDateWithTimezoneOffset();
            const maxTokenCount = parseInt(await this.getBlessingOption("ygg_tokens_limit", "5"));
            const tokenIssued: PassportAccessToken[] = await prisma.client.passportAccessToken.findMany({
                where: {
                    client_id: parseInt(token.clientId!),
                    user_id: parseInt(token.accountId),
                    name: ACCESS_TOKEN_NAME,
                    revoked: false,
                    expires_at: {
                        gte: date
                    }
                }
            });

            if (tokenIssued.length >= maxTokenCount) {
                tokenIssued.slice(0, tokenIssued.length - maxTokenCount + 1).forEach(async (token) => {
                    await prisma.client.passportAccessToken.update({
                        where: {
                            id: token.id
                        },
                        data: {
                            revoked: true,
                        }
                    });
                });
            }

            await prisma.client.passportAccessToken.create({
                data: {
                    id: token.jti,
                    client_id: parseInt(token.clientId!),
                    user_id: parseInt(token.accountId),
                    name: ACCESS_TOKEN_NAME,
                    scopes: JSON.stringify(token.extra?.scopes),
                    revoked: false,
                    created_at: date,
                    expires_at: new Date(date.getTime() + token.expiration * 1000),
                }
            });
        });

        provider.on('refresh_token.consumed', async (rotatedRefreshToken: oidc.RefreshToken) => {
            const passportRefreshToken = await prisma.client.passportRefreshToken.findFirst({
                where: {
                    id: rotatedRefreshToken.jti,
                    revoked: false,
                    expires_at: {
                        gte: getDateWithTimezoneOffset()
                    }
                },
                select: {
                    access_token_id: true,
                }
            });

            if (passportRefreshToken) {
                await prisma.client.passportAccessToken.update({
                    where: {
                        id: passportRefreshToken.access_token_id
                    },
                    data: {
                        revoked: true
                    }
                });

                await prisma.client.passportRefreshToken.update({
                    where: {
                        id: rotatedRefreshToken.jti
                    },
                    data: {
                        revoked: true
                    }
                });
            }
        });

        /*
            刷新 Access Token 时也没法吊销原先的 Access Token，因为 oidc-provider 根本不知道上次签发的 Access Token 是哪个
            所以要把 Refresh Token 对应的 Access Token 存起来，在刷新 Access Token 时手动吊销
        */
        provider.on('access_token.issued', this.saveRefreshTokenToPassport.bind(this));
        provider.on('refresh_token.saved', this.saveRefreshTokenToPassport.bind(this));

        this.siteUrl = siteUrl;
        this.provider = provider;
    }

    async findAccount(ctx: oidc.KoaContextWithOIDC, id: string, token?: oidc.AuthorizationCode | oidc.AccessToken | oidc.DeviceCode | oidc.RefreshToken): Promise<oidc.Account | undefined> {
        const grantId = ctx.oidc.result?.consent?.grantId ?? token?.grantId;

        const authCode = await this.prisma.client.passportAuthCode.findFirst({
            where: {
                id: grantId,
                user_id: parseInt(id),
            }
        });

        if (!authCode) {
            return undefined;
        }

        const grant = ctx.oidc.entities.Grant ?? await ctx.oidc.provider.Grant.find(grantId!);
        if (!grant) {
            return undefined;
        }

        const user = await this.prisma.client.user.findFirst({
            where: {
                uid: Number(authCode.user_id),
                verified: true,
                permission: {
                    not: -1
                }
            }
        });

        if (!user) {
            return undefined;
        }

        const userInfo: UserInfo = {
            sub: id,
            nickname: user.nickname,
            email: user.email,
            email_verified: Boolean(user.verified),
            picture: `${this.siteUrl}/avatar/user/${user.uid}`,
        };

        const scopes: string[] | undefined = grant.resources?.[BS_RESOURCE_INDICATOR]?.split(' ');

        if (scopes?.includes(YggCScopes.PROFILE_SELECT)) {
            const codeIdToUUID: CodeIdToUUID | null = await this.prisma.client.codeIdToUUID.findFirst({
                where: {
                    code_id: grantId
                }
            });
            if (!codeIdToUUID) {
                return undefined;
            }
            const uuid = await this.prisma.client.uUID.findFirst({
                where: {
                    uuid: codeIdToUUID.uuid
                },
                include: {
                    player: true
                }
            });
            if (!uuid) {
                return undefined;
            }
            userInfo.selectedProfile = {
                id: uuid.uuid,
                name: uuid.player!.name
            };
        }

        if (scopes?.includes(YggCScopes.PROFILE_READ)) {
            const players: Player[] = await this.prisma.client.player.findMany({
                where: {
                    uid: user.uid
                }
            });
            userInfo.availableProfiles = await Promise.all(players.map(async (player) => {
                const uuid: UUID | null = await this.prisma.client.uUID.findFirst({
                    where: {
                        pid: player.pid
                    }
                });
                return uuid ? { id: uuid.uuid, name: player.name } : null;
            })).then(profiles => profiles.filter(profile => profile !== null));
        }

        return {
            accountId: userInfo.sub,
            async claims(use: string, scope: string, claims: object, rejected: string[]) {
                return userInfo;
            }
        };
    }

    /* 
        oidc-provider 的事件触发很奇怪
        当通过请求授权签发 Access Token 时，会先触发 access_token.issued 事件，再触发 refresh_token.saved 事件
        但在刷新 Access Token 时，会先触发 refresh_token.saved 事件，再触发 access_token.issued 事件
        所以两个事件的监听器中都需要尝试 upsert，确保 Refresh Token 保存在 Laravel Passport 的数据表中
    */
    async saveRefreshTokenToPassport() {
        // @ts-ignore
        const ctx: oidc.OIDCContext | undefined = oidc.Provider.ctx?.oidc;

        if (ctx?.entities.AccessToken && ctx?.entities.RefreshToken) {
            const accessToken = ctx.entities.AccessToken;
            const refreshToken = ctx.entities.RefreshToken;
            await this.prisma.client.passportRefreshToken.upsert({
                where: {
                    id: refreshToken.jti
                },
                create: {
                    id: refreshToken.jti,
                    access_token_id: accessToken.jti,
                    revoked: false,
                    expires_at: new Date(getDateWithTimezoneOffset().getTime() + refreshToken.expiration * 1000),
                },
                update: {}
            });
        }
    }

    async getBlessingOption(name: string): Promise<string | null>;
    async getBlessingOption(name: string, defaultValue: string): Promise<string>;
    async getBlessingOption(name: string, defaultValue?: string): Promise<string | null> {
        const option = await this.prisma.client.option.findFirst({
            where: {
                name: name
            },
            select: {
                value: true
            }
        });

        if (!option) {
            if (defaultValue !== undefined) {
                return defaultValue;
            }
            return null;
        }

        return option.value;
    }
}
