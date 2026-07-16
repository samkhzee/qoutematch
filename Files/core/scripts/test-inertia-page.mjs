const html = await fetch('http://127.0.0.1:8000').then((r) => r.text());
const match = html.match(/<script data-page="app" type="application\/json">([\s\S]*?)<\/script>/);
if (!match) {
    console.log('NO_INERTIA_SCRIPT');
    process.exit(1);
}
try {
    const page = JSON.parse(match[1]);
    console.log('PARSE_OK', page.component);
} catch (e) {
    console.log('PARSE_FAIL', e.message);
    process.exit(1);
}
