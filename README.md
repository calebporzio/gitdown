![GitDown - a simple package to parse markdown in PHP](banner.png)

# GitDown
A simple package for parsing (GitHub Flavored) Markdown in PHP.

## WARNING
This package is a fraud. All it does is fire off your markdown to a [public GitHub API](https://developer.github.com/v3/markdown/) that returns the parsed result.

Knowing this, if you don't store the result, or take advantage of the provided caching features, you'll end up with slow page loads, or worse, rate-limit errors from GitHub.

Markdown parsing is super annoying, and this tradeoff is well worth it to me, I hope you embrace it as well.

## Installation

```bash
composer require calebporzio/gitdown
```

## TLDR;

```php
// Optionally set a GitHub Personal Access Token to increase rate-limit.
GitDown::setToken($token);

GitDown::parse($markdown);

// Uses Laravel's cache()->rememberForever() under the hood.
GitDown::parseAndCache($markdown);
```

Optionally, add the `@gitdown` snippet to your template's `<head>` section for GitHub markdown/code-syntax styling.

```html
<head>
    [...]
    @gitdown
</head>
```

## Authenticating With GitHub

Without authentication, GitHub will limit your API calls to 60 calls/hour. If you use authentication tokens, you can increase this limit to 5000 calls/minute. It is highly recommended that you use a "Personal Access Token" with this package. To obtain one, [click here](https://github.com/settings/tokens). (You can leave the permissions blank for this token.)

First, publish the package's config file.

`php artisan vendor:publish --provider="GitDown\GitDownServiceProvider"`

Then, add the following entry to your `.env` file.

```
[...]
GITHUB_TOKEN=your-token-here
```

## Usage
```php
GitDown::parse($markdown);

// Will be cached forever. (suggested)
GitDown::parseAndCache($markdown);

// Will be cached for 24 hours. (minutes in Laravel < 5.8, seconds otherwise)
GitDown::parseAndCache($markdown, $seconds = 86400);

// Pass in your own custom caching strategy.
GitDown::parseAndCache($markdown, function ($parse) {
    return Cache::rememberForever(sha1($markdown), function () use ($parse) {
        return $parse();
    });
});
```

## Non-Laravel Usage
You can set a GitHub Personal Access Token by passing it into the `GitDown`'s constructor.
`new GitDown\GitDown($token)`

```php
(new GitDown\GitDown($token))->parse($markdown);

// Pass in your own custom caching strategy.
(new GitDown\GitDown($token))->parseAndCache($markdown, function ($parse) {
    return Cache::rememberForever(sha1($markdown), function () use ($parse) {
        return $parse();
    });
});
```

## Markdown/Syntax CSS

Styling markdown with CSS has always been a bit of a pain for me. Not to mention trying to style syntax inside code blocks. Not to worry!

GitDown ships with all the CSS you need to make your markdown look exactly like it does on GitHub. Just add this code somewhere on your HTML page, preferably near your other stylesheets in the `<head>` section.

```html
<head>
    [...]
    @gitdown
</head>
```

**Non-Laravel**
```html
<head>
    [...]
    <style><?php echo GitDown\GitDown::styles(); ?></style>
</head>
```

Bam! That's all you need to make everything look good 🤙.

If echoing out CSS directly on your page doesn't sit well with you, you can add the styles to your stylesheet yourself using NPM.

`npm install primer-markdown github-syntax-light --save`

Now you can include the SCSS files in your Sass bundler:

```scss
@import "primer-markdown/index.scss";
// The relative directories may be a little different for you.
@import "./../../node_modules/github-syntax-light/lib/github-light.css";
```

## GitHub Flavored Markdown

To enable GFM parsing for GitDown, set the "context" entry in `config/gitdown.php` to a repository name.

```php
"context" => "your/repo",
```

## Enjoy!

Hope this makes your life easier. If it does, show the project some love on Twitter and tag me: [@calebporzio](https://twitter.com/calebporzio)
