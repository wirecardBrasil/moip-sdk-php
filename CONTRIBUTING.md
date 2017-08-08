# Contributing
:clap::tada: Thank you for taking the time to contribute! :tada::clap:

We really value your willingness to contribute to this project. In order to higher the chances of your contribution being accepted, please refer to the following guidelines!

## Steps

1. Fork it!
2. Create your feature branch: `git checkout -b feature/xyz develop`
3. Commit your changes according to our commit message standards: `git commit -am 'feat(xyz) Added new functionality'`
4. Push to your repo: `git push origin feature/xyz`
5. Submit a pull request to `develop`

## Workflow
This repo uses Gitflow as its branch management system. You can learn more about Gitflow [here](https://www.atlassian.com/git/tutorials/comparing-workflows#gitflow-workflow).
A few quick tips:
* All feature branches should be based on `develop` and have the format `feature/branch_name`. 
* Minor bug fixes should be based on `master` and have the format `hotfix/branch_name`.

### Commit Conventions
In order to make the changelog generation easier we recommend the use of messages based on [Conventional Commits](https://conventionalcommits.org/).

Examples:
```
feat(orders): added `XYZ` helper function

commit description

footer notes
```

```
refactor(orders): refactored `ABC` helper function

The behaviour of `ABC` was inconsistent and (...)

BREAKING CHANGE: return type of `ABC` is now `String`
```

```
docs: updated documentation in Request.php
```

