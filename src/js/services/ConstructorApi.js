const baseUrl = process.env.MIX_API_URL

export function getLexicon() {
    return fetch(baseUrl + 'lexicon/', {
        method: 'GET',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    })
        .then((response) => response.json())
        .then((responseData) => responseData)
        .catch((error) => console.warn(error))
}

export function getStoneTypes() {
    return fetch(baseUrl + 'stones/', {
        method: 'GET',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    })
        .then((response) => response.json())
        .then((responseData) => responseData)
        .catch((error) => console.warn(error))
}

export function getChains() {
    return fetch(baseUrl + 'chains/', {
        method: 'GET',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    })
        .then((response) => response.json())
        .then((responseData) => responseData)
        .catch((error) => console.warn(error))
}

export function getPendants() {
    return fetch(baseUrl + 'pendants/', {
        method: 'GET',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    })
        .then((response) => response.json())
        .then((responseData) => responseData)
        .catch((error) => console.warn(error))
}

export function getPendantsByRoot(rootId) {
    return fetch(baseUrl + 'pendants/' + rootId, {
        method: 'GET',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
    })
        .then((response) => response.json())
        .then((responseData) => responseData)
        .catch((error) => console.warn(error))
}

export function sendMail(data) {
    return fetch(baseUrl + 'mail/', {
        method: 'POST',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json;charset=utf-8',
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((responseData) => responseData)
        .catch((error) => console.warn(error))
}
