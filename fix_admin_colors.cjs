const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
    const files = fs.readdirSync(dir);
    files.forEach(f => {
        let dirPath = path.join(dir, f);
        let isDirectory = fs.statSync(dirPath).isDirectory();
        isDirectory ? walkDir(dirPath, callback) : callback(dirPath);
    });
}

const themeDir = path.resolve(__dirname, 'resources/themes/admin/gpma');

walkDir(themeDir, function (filePath) {
    if (!filePath.endsWith('.php') && !filePath.endsWith('.css') && !filePath.endsWith('.js')) return;

    let content = fs.readFileSync(filePath, 'utf8');
    let original = content;

    // Replace hex colors (ignoring case)
    content = content.replace(/#c37c54/gi, '#3B75B0');
    content = content.replace(/#d4956f/gi, '#6FA8DC');
    content = content.replace(/#1f1f1f/gi, '#1F3A6E');

    // Replace amber classes with mapped GPMA colors
    content = content.replace(/hover:bg-amber-700/g, 'hover:bg-[#2f5c8f]');
    content = content.replace(/bg-amber-700/g, 'bg-[#3B75B0]');
    content = content.replace(/text-amber-700/g, 'text-[#3B75B0]');
    content = content.replace(/bg-amber-50/g, 'bg-blue-50');
    content = content.replace(/hover:bg-amber-100/g, 'hover:bg-blue-100');
    content = content.replace(/hover:text-amber-800/g, 'hover:text-[#1F3A6E]');

    if (content !== original) {
        fs.writeFileSync(filePath, content, 'utf8');
        console.log('Updated: ' + filePath);
    }
});
