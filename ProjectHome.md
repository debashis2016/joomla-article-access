This plugin allows you to add access control to articles by defining parts of the article which are viewable by certain usergroups.

The different usergroups available are:

  1. guest
  1. registered
  1. author
  1. editor
  1. publisher
  1. manager
  1. administrator
  1. super admininistrator

In order to make a part of the article viewable by a certain group, edit the article as such:

```
{access view=registered}
Only registered users can view this portion of the article.
{/access}
```

To exclude a group from viewing an article, use the exclamation mark (!) before the group name.

```
{access view=!registered}
Any group other then registered can view this portion of the article.
{/access}
```

To add multiple groups, separate them with a comma (,).

```
{access view=registered,author,editor}
Only registered,author and editor usertypes can view this portion of the article.
{/access}
```

To add a any group between two groups (inclusive), use a hyphen (-).

```
{access view=registered-editor}
Only registered,author and editor usertypes can view this portion of the article.
{/access}
```

To add any group and above or below a group (inclusive) just leave the group to the right or left blank in order to match to the lowest or highest group.

```
{access view=-registered}
Only guests and registered users can view this portion of the article.
{/access}
```

```
{access view=author-}
Only authors and above can view this portion of the article.
{/access}
```