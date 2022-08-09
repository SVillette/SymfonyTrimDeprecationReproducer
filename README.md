# Trim Deprecation Reproducer

This package is a reproducer of a (potential) bug in SymfonyDoctrineBridge.

## Description
The bug is a deprecation that is triggered because the usage of a `null` argument to `\trim()` method in `\Symfony\Component\HttpKernel\DataCollector\LoggerDataCollector`.

To reproduce the bug, I needed to install a fresh project and require `orm-pack` and `debug`.

I added a monolog handler (see `config/packages/monolog.yaml`) that logs in `dev.deprecations.log` all triggered deprecations.
I added a unique controller that just render a basic template as the deprecation is not triggered without it.

When I visit the `/` route I get a deprecation warning about DbalLogger. This is NOT the issue I want to report here.
Then I click on log menu item in the debug bar and the trim deprecation is logged in `dev.deprecations.log` only at that time.

## Deprecation log
```
[2022-08-09T16:55:55.521167+02:00] deprecation.INFO: Deprecated: trim(): Passing null 
to parameter #1 ($string) of type string is deprecated {"exception":"[object] 
(ErrorException(code: 0): Deprecated: trim(): Passing null to parameter #1 
($string) of type string is deprecated at 
vendor/symfony/http-kernel/DataCollector/LoggerDataCollector.php:147)"} []
```

## Solution
This bug can be fixed in `vendor/symfony/http-kernel/DataCollector/LoggerDataCollector.php:147` by checking if 
`$log['channel']` is not null before using the `\trim()` method.

```php 
public function getFilters()
    {
        $filters = [
            'channel' => [],
...
        ];

        $allChannels = [];
        foreach ($this->getProcessedLogs() as $log) {
            // if (null === $log['channel'] || '' === trim($log['channel'])) {
            if ('' === trim($log['channel'])) {
                continue;
            }

            $allChannels[] = $log['channel'];
        }
...
        return $filters;
    }
```
