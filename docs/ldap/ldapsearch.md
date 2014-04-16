# Testing LDAP with ldapsearch

Anonymous:

```
 ldapsearch -x -H ldaps://ldap.example.org -b 'o=example.org' '(uid=testuser)'
```

Bind:

```
ldapsearch -x -H ldaps://ldap.example.org -b 'o=example.org' -D 'uid=udb,ou=special users,o=example.org' '(uid=testuser)'
```