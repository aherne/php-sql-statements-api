# PHPQueryingAPI

The purpose of a database abstraction layer, in simplest terms, is to decouple code dedicated to SQL statement generation from code dedicated (primarily)  to SQL statement execution (assigned to data access layer). Unlike data access layer’s case, PHP provides no native implementation of this layer, leaving it completely at programmers’ disposal to implement. 

SQL standard provides blueprints for each statements and vendors that implement the SQL standard (for example MySQL) can alter these blueprints or add their own. The goal of this API  is to implement all of these query blueprints programatically into a statement-oriented class structure. Furthermore, complex statement blueprints are in turn composed of separate clauses (each with its own syntax), of which some appear in more than one blueprint. This clearly calls for API to encapsulate clauses logic in separate clause-oriented class structure. Both statements and clauses may employ operators, which can be inherited from SQL standard or vendor specific. This clearly calls for API to hold operators as constants in operator-oriented class structure.

NOTE: 

1. Included in library release are standard SQL statements/clauses/operators as well as their MySQL extension. To support other vendors, use MySQL extension as an example of how to derive or add functionality.
2. Since the purpose of these classes is to simplify the process of querying, in order to maintain simplicity, a few cases were left out, so in rare circumstances you may still need to compose queries manually.
3. SQL programming sections (for example: stored procedures, functions and triggers @ MySQL) were put aside, because they are strictly vendor specific (cannot be abstracted to an SQL standard) and this library only abstracts querying.

This API, although totally decoupled, is <i>conceptual</i> part of SQL Suites, an integrated solution designed to cover all aspects of communication with SQL servers. To see the full documentation, please visit to SQL suites docs :

https://docs.google.com/document/d/1U5PtPyub4t273gB9gXoZTX7TQasjP6lj93kMcClovS4/edit# 