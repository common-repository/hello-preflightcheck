=== Hello Preflightcheck ===
Contributors: hellofuture.se
Tags: tests, testing, preflightcheck, checks, quality, qa
Requires at least: 2.7
Tested up to: 3.4.1
Stable tag: 1.0.4

Preflightcheck runs various php test scripts to avoid errors when developing and deploying. Preflightcheck is easily extensible.

== Description ==

Preflightcheck helps you avoid errors and improves the quality of your WordPress projects. The plugin adds a
‘Hello Preflightcheck’ submenu in the Tools menu in the WordPress admin interface. On this page all tests will
be executed displaying a test result for each test. The tests are found in hello-preflightcheck/tests inside your
plugin directory.

Please suggest your own useful tests and contribute to the development of this plugin. Just send an email to
olaf@hellofuture.se if you want us to add your tests to Hello Preflightcheck.

== Installation ==

1. Unzip the zip-archive you downloaded.
1. Upload the directory hello-preflightcheck (and all subdirectories) to the plugins directory (wp-content/plugins).
1. Activate the plugin through the 'Plugins' menu in the admin interface

Neither structure nor content of the database is changed.

== Frequently Asked Questions ==

= How do I run the tests? =

Just open the Hello Preflightcheck page found in the Tools section.

= How often are the tests executed? =

These tests are only executed by manually opening the Hello Preflightcheck page (found in the Tools section).
Therefore it's no problem if some of these tests take a bit of time.

= Should I write own tests? =

Definitely yes!

= How do I write my own tests? =

Every test lives in the tests directory (wp-content/plugins/hello-preflightcheck/tests) or in a
subdirectory. It has the following:

1. a JavaDoc/phpdoc style comment that provides the test description.
2. Some logic for the test itself.
3. Exactly one method of the following:

* `$check->info($message)`: for showing information without any evaluation
* `$check->success($message)`: for successful test results
* `$check->warning($message)`: for test results that might cause a problem
* `$check->error($message)`: for test results that definitely will cause a problem
* `$check->ignore()`: if no output is provided.

Additionally you may use `$check->showList()` to output an array as a list.

Have a look at the existing tests to see how it works.

= Can I remove tests? =

Yes, just throw them away. But why should you?

= Can I contribute tests? =

Yes, please! Just send me a mail at olaf@hellofuture.se with the test and I will add each useful test
as soon as I can.

== Screenshots ==

1. Example output of the test results

== Changelog ==

= 1.0.4 =

* used the new function wp_get_theme() instead of the deprecated get_current_theme() when exists
* removed Hello Future specific tests

= 1.0.3 =

* deactivated php syntax checking under a special (and strange) condition

= 1.0.2 =

* favicon test: changed severity from error to warning
* wordpress-version test: changed severity from warning to error
* timezone test: new test, that checks whether the timezone is the default timezone
* filler-text-in-templates text: removed grepping '@example' since it’s too generic
* uploads-folder test: skip directories that start with a dot

= 1.0.1 =

* changed icon on plugin page
* improved readme.txt

= 1.0.0 =

* Initial release
