# Redirect 404 Regex
This module provides the ability to create redirects for 404s based on a regex match.

## A typical use case
Assume you have migrated a number of news nodes from Drupal 7 to Drupal 9. Assume you only migrated news nodes created after January 1, 2020. Assume, in both cases, the path for news articles is /news/{node_title}

Links to news articles that were created on the Drupal 7 site prior to the migration date would produce a 404 on the new site. You may want to redirect all such 404s to the new /news path.

## How to use
This module works on an event subscriber that will only fire if the original request returns a 404.

Navigaet to /admin/config/search/redirect-404-regex-rule and create rules. The Regex Pattern will check whether the requested path matches the regex. If so, the reponse will be redirected to whatever path is provided in the Redirect Path field.

The Regex Pattern must start and end with `/`.

The Redirect Pattern must start with `/`.

If the requested path matches more than one rule, only the rule with the lowest weight will be apply. Rules with higher weights will be ignored.

These are configuration entities, so they can be exported with `drush cex`.

## An Example
Regex Pattern: /^\/news\//
Redirect Path: /news/

A request for /news/foo that 404s will be redirected to /news/.

A request for /news/bar that does not 404 will not be redirected.
