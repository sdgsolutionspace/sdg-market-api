// This file can be replaced during build by using the `fileReplacements` array.
// `ng build ---prod` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
  production: false,
  auth: {
    domain: 'github-trading.eu.auth0.com',
    CLIENT_ID: 'iSWtqfpvBycKK4hxa4cwTBeQdn36X8JF',
    CLIENT_DOMAIN: 'github-trading.eu.auth0.com',
    REDIRECT: 'http://localhost:4200/callback',
    AUDIENCE: 'https://github-trading.eu.auth0.com/api/v2/',
    LOGOUT_URL: '/',
    SCOPE: 'openid profile email'
  }
};

/*
 * In development mode, to ignore zone related error stack frames such as
 * `zone.run`, `zoneDelegate.invokeTask` for easier debugging, you can
 * import the following file, but please comment it out in production mode
 * because it will have performance impact when throw error
 */
// import 'zone.js/dist/zone-error';  // Included with Angular CLI.
