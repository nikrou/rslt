DIST=.dist
PLUGIN_NAME=rslt
SOURCE=./*
TARGET=../target
DESTINATION=root@kimsufi2:/var/www/nikrou.net/dc/plugins/$(PLUGIN_NAME)/
RSYNC=rsync -vrpcC --exclude-from=rsync_exclude

rsync:
	$(RSYNC) $(SOURCE) $(DESTINATION) -n

install:
	$(RSYNC) $(SOURCE) $(DESTINATION)

config: clean manifest
	mkdir -p $(DIST)/$(PLUGIN_NAME)
	cp -pr src default-templates _*.php CHANGELOG.md js css imgs LICENSE MANIFEST README.md locales BUGS index.php $(DIST)/$(PLUGIN_NAME)/; \
	find $(DIST) -name '*~' -exec rm \{\} \;

dist: config
	cd $(DIST); \
	mkdir -p $(TARGET); \
	zip -v -r9 $(TARGET)/plugin-$(PLUGIN_NAME)-$$(grep '/* Version' $(PLUGIN_NAME)/_define.php| cut -d"'" -f2).zip $(PLUGIN_NAME); \
	cd ..

manifest:
	@find ./ -type f|egrep -v '(*~|.git|.gitignore|.dist|data|vendor|target|modele|Makefile|rsync_exclude)'|sed -e 's/\.\///' -e 's/\(.*\)/$(PLUGIN_NAME)\/&/'> ./MANIFEST

clean:
	rm -fr $(DIST)

dist-clean:
	rm -fr $(DESTINATION)

##
XGETTEXT=/usr/bin/xgettext -k__ -j -L PHP --from-code=utf-8 -o locales/templates/messages.pot
GETTEXT_FORMAT=/usr/bin/msgfmt
GETTEXT_MERGE=/usr/bin/msgmerge

SEARCH_PATTERN=(*.php|*.tpl)$$
EXCLUDE_PATTERN=(vendor|target|.dist)

search:
	@find ./ -type f|egrep '$(SEARCH_PATTERN)'|egrep -v '$(EXCLUDE_PATTERN)'|while read f;do $(XGETTEXT) $$f;done

merge:
	@for l in locales/*/*.po;				\
	do								\
	$(GETTEXT_MERGE) $$l locales/templates/messages.pot --output=$$l	;\
	done
