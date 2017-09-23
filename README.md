# ACTIVE-REST #
This is a library to make CRUD's with your REST API. With this package, and some framework like Slim. You can abstract operations: POST,PUT,PATCH,DELETE,FIND,FINDONE at your Routes.

## ORM OPERATIONS
* **Find:** Implement the `select` operation;
* **FindOne:** Implement the `search` operation;
* **Post:** Implement the `create` operation;
* **Patch:** Implement the `update by replace` operation;
* **Put:** Implement the `update by increment` operation;
* **Replicate:** Implement the `replicate` operation.  
*The behavior is clone and update incremental keys*;
* **Del:** Implement the `delete` operation;
* **Disable:** Implement the `disable` operation.;
*The behavior is disable the register by status change*;
* **Last:** Implement the `last` operation;
*The behavior is find the last register at repository.*;
