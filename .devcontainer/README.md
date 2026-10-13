# Devcontainer Structure

This devcontainer is configured with the goal to be portable across operating systems and work with docker as well as podman.
The most important and probably confusing decisions were taken as follows:


## Rootless User ID mapping
It is best practice not to run as root inside the container. To facilitate development (e.g. adding packets), it is however beneficial to give the non-root user sudo permissions. For now we use the default vscode dev container, which implements this for the `vscode` user with ID `1000:1000`.

It is common to just use `userns_mode: keep-id` and force the user in the container to change their UID:GID, silently relying on `updateRemoteUserUID` defaulting to true However this can cause conflicts on systems with high UIDs and makes the containers internal state dependant on the host environment.

A slightly more complex but portable and explicit approach is to re-map the host-side UID:GID to the in-container default UID:GID with `keep-id:uid=1000,gid=1000`. This is also reflected by the `remoteUser` and `containerUser` settings as well as the `USER` line in the `Containerfile`.


## Workspace Mounting

Betwen VSCode and JetBrains, seems to be a discrepancy (as of October 2026):
VSCode accepts a `workspaceFolder` in `devcontainer.json` and expects it to be mounted within `compose.yml`. JetBrains instead performs a mount themselves, yielding a double mount which prevents podman from starting the container.

One workaround found so far is to explicitly add the `workspaceMount` to the `devcontainer.json` _although this violates the schema_. Both VSCode and JetBrains still seem to honor this directive and both create a correct mount. This might actually be better in case of directly running the container form a git url, as some tools might use a workspace
working tree (or volume) separate from the location of the `compose.yml`.

Another way, which was chosen for now, does not violate the schema: Use the regular `mounts` section to add the workspace mount.


## CPU Shares Limiting

Especially when working on a notebook, some IDEs and extensions might run hughe indexing jobs, which might cause the overall machine to soft-freeze.
Since this dev environment itself is not performance critical, we set a CPU limit in `compose.yaml` in order to soften the impact of the container.

