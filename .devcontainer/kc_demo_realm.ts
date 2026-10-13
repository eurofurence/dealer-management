// Example snippet to initialize keycloak - NOT IN USE YET.
import KcAdminClient from '@keycloak/keycloak-admin-client'
import type RealmRepresentation from '@keycloak/keycloak-admin-client/lib/defs/realmRepresentation'
import type ClientRepresentation from '@keycloak/keycloak-admin-client/lib/defs/clientRepresentation'
import type UserRepresentation from '@keycloak/keycloak-admin-client/lib/defs/userRepresentation'

/* -- Declare desired states -- */

const realm = 'ef-dev'

const desiredRealmState: RealmRepresentation = {
    // Make a unique and well-distinguishable dev-realm name
    displayName: 'EFDD Dev',
    displayNameHtml: '<strong>EFDD</strong><span style="color:goldenrod;">Dev</span>',
    // Basic settings
    defaultSignatureAlgorithm: 'RS256',
    enabled: true,
    // User Interaction
    registrationAllowed: true,
    resetPasswordAllowed: true,
    editUsernameAllowed: true,
    verifyEmail: true,
    // Email settings
    smtpServer: {
        from: 'no-reply-sso@localhost',
        fromDisplayName: 'EFDD Dev SSO',
        host: 'mailpit',
        port: '1025',
    },
    // Localization
    defaultLocale: 'de',
    supportedLocales: ['de', 'en'],
}

const desiredClientStates: ClientRepresentation[] = [
    {
        clientId: 'dealer-dev-app',
        name: 'Dealer\'s Den Dev App',
        enabled: true,
        publicClient: true,
        description:
            'Demo App Config - to be adjusted.',
        rootUrl: 'https://localhost:8443/',
        redirectUris: ['https://localhost:8443/*'],
        webOrigins: ['https://localhost:8443'],
        // adminUrl: 'https://localhost:8443/',
        attributes: {
            'pkce.code.challenge.method': 'S256',
        },
    },
]

const desiredUserStates: (UserRepresentation & { password: string | undefined })[] = [
    {
        username: 'diddy',
        firstName: 'Diddy',
        lastName: 'Dealerson',
        email: 'diddy@localhost',
        emailVerified: true,
        enabled: true,
        password: 'diddy',
    },
]

const debug_fetch: boolean = false

/* -- Execute -- */

if (debug_fetch) {
    // Rebind the global fetch function to intercept and display a failing requests
    const _fetch = fetch
    // @ts-ignore
    fetch = async (input, init) => {
        const result = await _fetch(input, init)
        if (result.status > 299) {
            console.log({
                FETCH_WITH_ERROR: { result, input, init, response_body: await result.text() },
            })
        }
        return result
    }
}

const log = console.log

log('Preparing Keycloak Dev Realm - Connecting ...')

const ac = new KcAdminClient({
    baseUrl: 'http://keycloak:8280',
    realmName: 'master',
})

log('Logging in ...')
await ac.auth({
    username: 'admin',
    password: 'Unsafe621',
    grantType: 'password',
    clientId: 'admin-cli',
})
log(`Connected. Checking realm status ...`)

if (!(await ac.realms.findOne({ realm }))) {
    log(`Creating realm...`)
    await ac.realms.create({
        realm,
        ...desiredRealmState,
    })
} else {
    log(`Updating realm...`)
    await ac.realms.update({ realm }, desiredRealmState)
}

for (const clientState of desiredClientStates) {
    const existing = await ac.clients.find({
        clientId: clientState.clientId,
        realm,
    })
    if (existing.length == 0) {
        log(`Creating client ${clientState.clientId}...`)
        const { id } = await ac.clients.create({
            realm,
            ...clientState,
        })
        log(`Client ${clientState.clientId} created as ${id}`)
    } else if (existing.length == 1 && existing[0].id !== undefined) {
        const id = existing[0].id
        log(`Updating client ${clientState.clientId} at ${id}...`)
        await ac.clients.update({ id, realm }, clientState)
    } else {
        log(`ERROR: Multiple or invalid existing clients found for ${clientState.clientId}.`)
        log({ existing })
    }
}

async function reset_user_password(id: string, password: string | undefined) {
    if (password == undefined) return
    log(`Resetting password for user ${id}`)
    await ac.users.resetPassword({
        realm,
        id,
        credential: {
            temporary: false,
            type: 'password',
            value: password,
        },
    })
}

for (const userState of desiredUserStates) {
    const { password, username, ...otherState } = userState
    const existing = await ac.users.find({ username, realm })
    if (existing.length == 0) {
        log(`Creating user ${username}...`)
        const { id } = await ac.users.create({ realm, username, ...otherState })
        log(`Client ${username} created as ${id}`)
        await reset_user_password(id, password)
    } else if (existing.length == 1 && existing[0].id !== undefined) {
        const id = existing[0].id
        log(`Updating client ${username} at ${id}...`)
        await ac.users.update({ id, realm }, otherState)
        await reset_user_password(id, password)
    } else {
        log(`ERROR: Multiple or invalid existing users found for ${username}.`)
        log({ existing })
    }
}

log(`Keycloak Dev Realm should be up-to-date now. Please note: Extra objects are not removed.`)
