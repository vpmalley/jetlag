# Needs for authoring

- List all elements authored by a user => method getAuthorsForUser(userId) returning a list of authorIds
- List all users (public aliases) for an element => method getUsers(userId) returning a list of UserPublic
- Update the authors of an element (only for owners) => method updateAuthorUsers(array of userId)
- Determine the role of a user regarding an element : owner, writer, reader (if element is private) => methods isOwner(userId, authorId), isWriter(userId, authorId), isReader(userId, authorId) returning booleans
- Determine the visibility of an element (public, private for some readers) => see method isReader

Regarding any HTTP request:
- using the logged in user's id, a call to one of isReader, isWriter or isOwner should be made (for reading rights, the visibility of the element might be enough)

Regarding the update of the authors and the way it is stored:
- the table "authors" contains lines with an authorId, a userId and a role (so far, one of owner, writer, reader)
- updating the users of an element requires first that the request be made by a logged user. The user must be an owner of the element.
