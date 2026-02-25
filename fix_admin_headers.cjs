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

    // Find all h1, h2, h3 tags with text-gray-800, text-gray-900, text-slate-800, text-black inside their class attribute
    content = content.replace(/(<(h1|h2|h3)[^>]*class="[^"]*)text-(gray|slate)-(800|900|black)([^"]*">)/gi, '$1text-[#1F3A6E]$5');
    content = content.replace(/(<(h1|h2|h3)[^>]*class="[^"]*)text-black([^"]*">)/gi, '$1text-[#1F3A6E]$3');

    if (content !== original) {
        fs.writeFileSync(filePath, content, 'utf8');
        console.log('Updated: ' + filePath);
    }
}

const themeDir = path.resolve(__dirname, 'resources/themes/admin/gpma');
walkDir(themeDir, processFile);

const modulesDir = path.resolve(__dirname, 'modules');
walkDir(modulesDir, function (filePath) {
    // Only target admin templates in modules
    const normalizedPath = filePath.replace(/\\/g, '/').toLowerCase();
    if (normalizedPath.includes('/views/admin/')) {
        processFile(filePath);
    }
});
