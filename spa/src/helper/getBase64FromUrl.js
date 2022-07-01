export default async function getBase64FromUrl(url) {
    const data = await fetch(url, { cache: "no-cache" })
    const blob = await data.blob()

    return new Promise(resolve => {
        const reader = new FileReader()
        reader.readAsDataURL(blob)
        reader.onloadend = () => resolve(reader.result)
    })
}
