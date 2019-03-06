![Gitdown - a simple package to parse markdown in PHP](banner.png)

# GitDown
A simple package to parse Github Flavored Markdown in PHP

## WARNING
This package is a fraud. All it does is fire off your markdown to a public GitHub API that returns the parsed result.

I personally think this is not a bug, but a feature, because the markdown is actually getting parsed by GitHub itself, and not a third-party library.

However, each time you call `GitDown::parse()` you are hitting a live endpoint. Because of this, it is STRONGLY recommended that you store the parsed output or cache it.

## Installation

```bash
composer require calebporzio/gitdown
```

## Usage

```php
CalebPorzio\GitDown::parse('# Some Markdown');
```

## Making it look good

Styling markdown with CSS has always been a bit of a pain for me. Not to mention trying to style syntax inside code blocks. Not to worry!

GitDown ships with all the CSS you need to make your markdown look exactly like it does on GitHub. Just add this code somewhere on your HTML page, preferably near your other stylesheets.

```php
<style><?php echo CalebPorzio\GitDown::styles(); ?></style>
```

Bam! That's all you need to make everything look good 🤙.

If echoing out CSS directly on your page doesn't sit well with you, you can add the styles to your stylesheet yourself using NPM.

`npm install primer-markdown github-syntax-light --save`

Now you can include the SCSS files in your Sass bundler:

```
@import "primer-markdown/index.scss";
// The relative directories may be a little different for you.
@import "./../../node_modules/github-syntax-light/lib/github-light.css";
```

## Enjoy!

Hope this makes your life easier. If it does, show the project some love on Twitter and tag me: [@calebporzio](https://twitter.com/calebporzio)
