const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
    if (!fs.existsSync(dir)) return;
    const files = fs.readdirSync(dir);
    files.forEach(f => {
        let dirPath = path.join(dir, f);
        let isDirectory = fs.statSync(dirPath).isDirectory();
        isDirectory ? walkDir(dirPath, callback) : callback(dirPath);
    });
}

function processFile(filePath) {
    if (!filePath.endsWith('.blade.php')) return;

    let content = fs.readFileSync(filePath, 'utf8');
    let original = content;

    // Pattern looking for: <div class="..." dir="ltr"> \s* {{ $items->links() }} \s* </div>
    // Actually, sometimes it's just <div class="mt-6" dir="ltr">
    // Let's replace `dir="ltr"` ONLY if the next non-whitespace thing is `{{ $...->links() }}`
    content = content.replace(/(<div[^>]*class="mt-\d+"[^>]*)dir="ltr"([^>]*>\s*\{\{\s*\$[^}]*links\(\)\s*\}\}\s*<\/div>)/gi, '$1$2');

    if (content !== original) {
        fs.writeFileSync(filePath, content, 'utf8');
        console.log('Fixed pagination wrapper in: ' + filePath);
    }
}

const viewsDir = path.resolve(__dirname, 'resources/themes/admin/gpma/views');
const modulesDir = path.resolve(__dirname, 'modules');

walkDir(viewsDir, processFile);
walkDir(modulesDir, processFile);
