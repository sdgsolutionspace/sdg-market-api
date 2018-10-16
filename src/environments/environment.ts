// This file can be replaced during build by using the `fileReplacements` array.
// `ng build ---prod` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
    production: false,
    baseUrl: 'http://localhost:8000',
    baseAPIUrl: 'http://localhost:8000/api/v1/',
    // baseUrl: 'http://github-trading.local',
    // baseAPIUrl: 'http://github-trading.local/api/v1/',
    githubAuth: {
        URL: 'https://github.com/login/oauth/authorize',
        CLIENT_ID: 'Iv1.54319c9b16b131e3',
        CLIENT_DOMAIN: 'github-trading.eu.auth0.com',
        REDIRECT_URI: 'http://localhost:4200/callback',
        SCOPE: 'user user:email repo'
    },
    localStorageJWT: 'git-trading-jwt'
};

/*
 * In development mode, to ignore zone related error stack frames such as
 * `zone.run`, `zoneDelegate.invokeTask` for easier debugging, you can
 * import the following file, but please comment it out in production mode
 * because it will have performance impact when throw error
 */
// import 'zone.js/dist/zone-error';  // Included with Angular CLI.
