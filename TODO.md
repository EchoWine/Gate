TODO list

General:

- [x] Cleanup src/Session
- [ ] Move part of Auth in CoreWine
- [x] Update Auth with new ORM
- [ ] Admin/UserController
- [ ] Admin/ExampleController: Example/Testing for ORM
- [ ] Throw exception for unauthorized client in route /admin
- [ ] Handle exception for unauthorized client in route /admin and redirect to login
- [x] Throw exception php version < 7
- [ ] Move route/test in TestControler
- [ ] CoreWine\Response html/json
- [ ] Add __call exception
- [ ] Add middleware

ORM:
- [x] Move part of 'src/Item' under 'lib/CoreWine/Item'
- [x] Rename 'src/item' in src/Api' and cleanup
- [ ] Throw exception when ORM/Model relations is null and a field is invoked
- [ ] Resolve problem about column/repository of field
- [x] Make clear distinction between Field/Entity and Field/Schema
- [x] Make clear distinction between ORM/Entity and ORM/Schema
- [ ] Improve ::copy in relations

- [x] Basic relations between object in ORM
- [x] Get relation N to 1 ORM
- [x] Save relations N to 1 ORM
- [x] Get relations 1 to N ORM
- [x] Save relations 1 to N ORM
- [ ] Improve relations (select which relations, get relation after retrieved)
- [x] Create basic ORM
	- [x] Create
	- [x] Save
	- [x] New
	- [x] Fill
	- [x] Retrieve (get, first, etc..)
	- [x] Delete
	- [x] Copy
	- [ ] Fields:
		- [x] ID
		- [x] Integer
		- [x] String
		- [ ] Text
		- [ ] Float
		- [ ] Date
		- [ ] Time
		- [ ] DateTime
		- [x] Timestamp

- src/Api:
	- [x] index
		- [x] sort
		- [x] pagination
		- [x] search
	- [x] add
	- [x] get
	- [x] edit
	- [x] delete
	- [x] refactoring

DataBase:
- [ ] Option to cleanup table model, defined previously, and not exist
- [ ] Migrations
- [ ] Soft/Hard alter table (hard: delete not declared column, only for dev, not for prod)
- [ ] Backup of database for every alteration in folder

