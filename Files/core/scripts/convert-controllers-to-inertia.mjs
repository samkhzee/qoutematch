/**
 * Convert return view(...) to InertiaBridge in controllers.
 * Run: node scripts/convert-controllers-to-inertia.mjs
 */
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const base = path.join(__dirname, '..');

function bridgeType(view) {
    if (view.includes('$') || view.includes('{')) return null;
    if (view.startsWith('admin.')) return 'admin';
    if (view.startsWith('Template::buyer.auth.') || view.startsWith('Template::user.auth.')) return 'auth';
    if (view.startsWith('Template::buyer.')) return 'buyer';
    if (view.startsWith('Template::user.')) return 'master';
    if (view.startsWith('Template::')) return 'frontend';
    return 'bare';
}

function convertContent(content) {
    let changed = false;

    // return view('x', compact(...));
    content = content.replace(
        /return\s+view\s*\(\s*(['"])([^'"]+)\1\s*,\s*(compact\s*\([^)]+\))\s*\)\s*;/g,
        (match, _q, view, args) => {
            const type = bridgeType(view);
            if (!type) return match;
            changed = true;
            return `return \\App\\Lib\\InertiaBridge::${type}('${view}', ${args});`;
        }
    );

    // return view('x')->with([...]) or ->with('a', $b)
    content = content.replace(
        /return\s+view\s*\(\s*(['"])([^'"]+)\1\s*\)\s*->with\s*\(([\s\S]*?)\)\s*;/g,
        (match, _q, view, withArgs) => {
            const type = bridgeType(view);
            if (!type) return match;
            changed = true;
            const trimmed = withArgs.trim();
            if (trimmed.startsWith('[')) {
                return `return \\App\\Lib\\InertiaBridge::${type}('${view}', ${trimmed});`;
            }
            return `return \\App\\Lib\\InertiaBridge::${type}('${view}', compact(${trimmed.replace(/^compact\s*\(|\)$/g, '')}));`;
        }
    );

    // return view("Template::$x" . '.y', compact(...)) - dynamic template userType
    content = content.replace(
        /return\s+view\s*\(\s*"Template::\$this->userType"\s*\.\s*'([^']+)'\s*,\s*(compact\s*\([^)]+\))\s*\)\s*;/g,
        (match, suffix, args) => {
            changed = true;
            return `return \\App\\Lib\\InertiaBridge::forUserType($this->userType, '${suffix}', ${args});`;
        }
    );

    return { content, changed };
}

function walkDir(dir, files = []) {
    for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
        const full = path.join(dir, entry.name);
        if (entry.isDirectory()) walkDir(full, files);
        else if (entry.name.endsWith('.php')) files.push(full);
    }
    return files;
}

const targets = [
    path.join(base, 'app/Http/Controllers'),
    path.join(base, 'app/Traits/SupportTicketManager.php'),
];

let fileCount = 0;
for (const target of targets) {
    const files = fs.statSync(target).isDirectory() ? walkDir(target) : [target];
    for (const file of files) {
        if (file.includes('View/Components')) continue;
        const original = fs.readFileSync(file, 'utf8');
        const { content, changed } = convertContent(original);
        if (changed) {
            fs.writeFileSync(file, content);
            fileCount++;
            console.log('Updated:', path.relative(base, file));
        }
    }
}

console.log(`\nDone. Updated ${fileCount} files.`);
