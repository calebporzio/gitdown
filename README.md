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
$markdown = file_get_contents('./some-file.md');

CalebPorzio\GitDown::parse($markdown);
```

## Styling the markdown
