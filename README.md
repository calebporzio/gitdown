# GitDown
A simple package to parse Github Flavored Markdown in PHP

## WARNING
This package is a fraud. All it does is fire off your markdown to a public GitHub API that returns the parsed result.

I personally think this is a feature because it's true, accurate GitHub markdown parsing. However, each time you call `GitDown::parse()` you are hitting a live endpoint. Because of this, it is STRONGLY recommended that you store the parsed output or cache it.

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
