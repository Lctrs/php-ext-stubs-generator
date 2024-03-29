.PHONY: it
it: coding-standards dependency-analysis static-code-analysis tests ## Runs the coding-standards, dependency-analysis, static-code-analysis, and tests targets

.PHONY: code-coverage
code-coverage: vendor ## Collects coverage from running unit tests with phpunit/phpunit
	mkdir -p .build/phpunit
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml.dist --coverage-text

.PHONY: coding-standards
coding-standards: vendor ## Normalizes composer.json with ergebnis/composer-normalize, lints YAML files with yamllint, converts YAML configuration to PHP format, and fixes code style issues with squizlabs/php_codesniffer
	composer normalize
	yamllint -c .yamllint.yaml --strict .
	vendor/bin/config-transformer config/
	mkdir -p .build/php_codesniffer
	vendor/bin/phpcbf
	vendor/bin/phpcs

.PHONY: dependency-analysis
dependency-analysis: vendor .tools/composer-require-checker/vendor ## Runs a dependency analysis with maglnet/composer-require-checker
	.tools/composer-require-checker/vendor/bin/composer-require-checker check --config-file=$(shell pwd)/composer-require-checker.json

.tools/composer-require-checker/vendor: .tools/composer-require-checker/composer.json .tools/composer-require-checker/composer.lock
	composer install --no-interaction --no-progress --working-dir=".tools/composer-require-checker/"

.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: mutation-tests
mutation-tests: vendor .tools/infection/vendor ## Runs mutation tests with infection/infection
	mkdir -p .build/infection
	.tools/infection/vendor/bin/infection --configuration=infection.json.dist

.tools/infection/vendor: .tools/infection/composer.json .tools/infection/composer.lock
	composer install --no-interaction --no-progress --working-dir=".tools/infection/"

.PHONY: static-code-analysis
static-code-analysis: vendor ## Runs a static code analysis with phpstan/phpstan and vimeo/psalm
	mkdir -p .build/phpstan
	vendor/bin/phpstan analyse --configuration=phpstan.neon.dist --memory-limit=-1
	mkdir -p .build/psalm
	vendor/bin/psalm --config=psalm.xml --diff --show-info=false --stats --threads=4

.PHONY: static-code-analysis-baseline
static-code-analysis-baseline: vendor ## Generates a baseline for static code analysis with phpstan/phpstan and vimeo/psalm
	mkdir -p .build/phpstan
	vendor/bin/phpstan analyze --allow-empty-baseline --configuration=phpstan.neon.dist --generate-baseline=phpstan-baseline.neon --memory-limit=-1
	mkdir -p .build/psalm
	vendor/bin/psalm --config=psalm.xml --set-baseline=psalm-baseline.xml

.PHONY: tests
tests: vendor ## Runs unit, and integration tests with phpunit/phpunit
	mkdir -p .build/phpunit
	vendor/bin/phpunit --configuration=test/Unit/phpunit.xml.dist
	vendor/bin/phpunit --configuration=test/Integration/phpunit.xml.dist

vendor: composer.json composer.lock
	composer validate --strict
	composer install --no-interaction --no-progress
