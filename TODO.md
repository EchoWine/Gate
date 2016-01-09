TODO list

Version 1.0

- [ ] .htaccess
- [ ] bin/admin

Library:
	- [x] DataBase
	- [x] TemplateEngine
	- [x] ModuleManager
	- [ ] Lang
	- [ ] Model, Controller, View

Modules: 
	- [ ] Auth
		- [x] Authentication
		- [ ] Registration
		- [ ] Recovery

	- [ ] Administration
		- [x] Credential
			- [ ] Connect with Auth module
		- [ ] Profile
		- [ ] Permission single & group
		- [ ] Log
		- [ ] Backup
		- [x] SystemInfo
		- [ ] Session
			- [ ] Disable alter
			- [x] Join with credential in select
			- [x] Disable alter operation (edit, add, copy ecc...)
			- [ ] Retrieve from Auth module name column

	- [ ] Item/Holding

	- [ ] Custom Page

	- [ ] Item
		- [x] Action Page
		- [x] Add
		- [x] Add as
		- [x] Copy single
		- [x] Copy multiple
		- [x] Delete single
		- [x] Delete multiple
		- [x] Edit single
		- [x] Edit multiple
		- [x] List
		- [x] Page 
		- [x] Search single
		- [x] Search multiple
		- [x] OrderBy
		- [x] View
		- [ ] Import
		- [ ] Export
		- [ ] Format
		- [x] checkForm
		- [x] checkExists
		- [x] response system
		- [x] checkForm return false => inputValue = $_POST
		- [x] Disabilitare operazioni multiple se checkbox non attivi
		- [x] Are you sure? Messaggio di conferma per operazioni pericolose "eliminazione"
		- [x] Mostra risultati 5,25,100
		- [ ] checkUnique for add,edit
		- [ ] Rollback
		- [ ] Field
			- [ ] Sort
			- [ ] Image

Version 2.0

- [ ] bin/public

Modules:
	- [ ] Gallery
	- [ ] Mail
	- [ ] PDF
