BIN_DIR = vendor/bin
VENDOR_DIR = vendor
COMPOSER_FILE = composer.phar
COMPOSER = php $(COMPOSER_FILE)
PHPUNIT = $(BIN_DIR)/phpunit
PHPUNIT_XML = phpunit.xml
PHPCS = $(BIN_DIR)/phpcs
PHPCS_STANDARD = PSR2
TMP_DIR = tmp
CURRENT_BRANCH := $(shell git branch | grep '*' | cut -d ' ' -f 2)
BRANCHES := $(shell git branch | grep -v "$(CURRENT_BRANCH)" | tr -d " " | tr "\\n" " ")
GIT_STAGE := $(shell git status -s | wc -l | tr -d " ")

.check-composer:
	@echo "Checking if Composer is installed..."
	@test -f $(COMPOSER_FILE) || curl -s http://getcomposer.org/installer | php;

.check-installation: .check-composer
	@echo "Checking for vendor directory..."
	@test -d $(VENDOR_DIR) || make install
	
.check-no-changes:
	@echo "Checking if git stage is clean..."
	@test $(GIT_STAGE) -eq "0" || exit 10;
	@echo "Git stage is clean."

clean:
	@echo "Removing Composer..."
	rm -f $(COMPOSER_FILE)
	rm -f composer.lock
	rm -rf $(VENDOR_DIR)
	rm -rf $(TMP_DIR)/*

test: .check-installation
	$(PHPUNIT) -c tests

#test-branches: .check-no-changes
#	@echo "Current branch: $(CURRENT_BRANCH)";
#	@echo "Branches to run on: $(BRANCHES)"
#	@$(foreach branch,$(BRANCHES), git checkout $(branch) & test -f Makefile & make test)

testdox: .check-installation
	$(PHPUNIT) --testdox -c tests

coverage: .check-installation
	$(PHPUNIT) --coverage-text -c tests

coverage-html: .check-installation
	$(PHPUNIT) --coverage-html ../$(TMP_DIR)/report -c tests
	
install: clean .check-composer
	@echo "Executing a composer installation of development dependencies.."
	$(COMPOSER) install --dev

update: .check-installation
	@echo "Executing a composer update of development dependencies.."
	$(COMPOSER) update --dev

code-sniffer: .check-installation
	$(PHPCS) --standard=$(PHPCS_STANDARD) module/

code-sniffer-report: .check-installation
	$(PHPCS) --report-summary --report-source --report-gitblame --standard=$(PHPCS_STANDARD) module/

.PHONY: test clean
