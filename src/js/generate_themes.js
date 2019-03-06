const adaptor = require('github-syntax-theme-generator/lib/adapters/css.js');

const theme = require('./themes/palenight.json');

console.log(adaptor(theme))
