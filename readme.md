### Introduction

This application is built on top of the Laravel 5.6, relying on a few dependencies :

- `graham-campbell/github` (a helper library to interact with `knplabs/github-api`)
- `madewithlove/illuminate-psr-cache-bridge` (optional cache bridge)

### Rate limiting

By default, no authorization keys are provided. According to github documentation, the limits for unauthenticated requests would be 60 per hour.

### Application workflow

There is only one route `GET /search` which takes 4 input parameters : 

1. `query` string
2. `page` int
3. `per_page` int
4. `sorting` string

The response is the json result from Github api v3 endpoint `/search/code`; only 'owner', 'repository' and 'file' fields are returned.

`App\Repositories\Github` is a class that interacts with Github API via `graham-campbell/github` package. It can also cache the requests locally, on demand.

It also implements `App\Contracts\RepositoryContract` which takes the key-role in managing the flexibility when we need to easily switch the API source.

`App\Repositories\Github` is bind to the interface `App\Contracts\RepositoryContract` in the service provider, which allow us to implement another source according to current contract. In this way we can easily switch our provider without touching any existing code. To switch the provider, all we need to do is replace the class name in the `RepositoryServiceProvider` with the new one, implementing the corresponding contract.

Thus, anytime we ask laravel for a provider instance which implements `App\Contracts\RepositoryContract`, we we'll get it from the service container. We inject this instance in the __construct method of our controller.

### Exception handler

The application will catch the exceptions thrown by inconsistent requests towards Github API. It may be also useful to keep track of the special headers (`X-RateLimit-Limit`, `X-RateLimit-Remaining`, `X-RateLimit-Reset`) which may help us to understand the current request limits we are running on.

### Tests

There are 2 basic tests intended to verify the routes consistency. The second test `testSearch()` will make a request to `/search` route and will check the consistency of the data.