# Shows the list of available just commands (default if no command is given)
help:
    @just --list

# Run the symfony CLI development server
_symfony-serve:
    symfony serve --no-tls --port=8000 --allow-http --listen-ip=127.0.0.1

# Run frontend proxy to combine services
_caddy:
    caddy run --config .devcontainer/Caddyfile

_CADDY_BASE_URL:="${CADDY_DOMAIN:-localhost}:${CADDY_HTTPS_PORT:-8443}"
# Helper to show an informative print, delayed some seconds so it shows after the services are up
_caddy_info:
    @sleep 5
    @echo "---"
    @echo "Note: To access the app via caddy, ensure your IDE forwards port 8443 (TCP, ideally also UDP)"
    @echo "Then: Visit https://{{_CADDY_BASE_URL}}/ to view the app."
    @echo "The Keycloak service is proxied at https://sso.{{_CADDY_BASE_URL}}/"
    @echo "The Mailpit UI is proxied at https://mailpit.{{_CADDY_BASE_URL}}/"
    @echo "---"

# Argument for parallel --tagstring to colorize just the tag, so we can still have tool output
# use colors. Also parallel --color does not work so nice on darkmode. We colorize the whole line.
# Color back to normal would be \u{1B}[0m at the end
_CTAG_STR := "\u{1B}[0m\u{1B}[3{#}m[{}]"

# Start all app services in parallel, allowing to fully use the app in dev-mode via caddy
serve:
    @echo "Starting services ..."
    @DEFAULT_URI="https://{{_CADDY_BASE_URL}}" parallel --tagstring "{{_CTAG_STR}}" --line-buffer just ::: _caddy_info _caddy _symfony-serve

# Run all test cases
test:
    @echo "Running PHPUnit"
    bin/phpunit

# Shorthand for "php bin/console"
[positional-arguments]
@console *args:
    php bin/console "$@"

# Define the port selector for the fuser command in why, stop and stop-selective
fuser_known_ports := "8000/tcp 5173/tcp 8443/tcp 8443/udp 8080/tcp"

# Check the devcontainer environment for common issues that prevent working normally
why:
    @echo "Checkin if ports currently in use/blocked by running processes"
    @-fuser --verbose {{fuser_known_ports}} && \
    echo "{{YELLOW}} To stop these processes, run: {{BOLD}}just stop{{NORMAL}} or {{BOLD}}just stop-some{{NORMAL}}" || \
    echo "{{BOLD}}{{GREEN}}  [OK] No typical ports in use/blocked{{NORMAL}}"
    @echo "Checkin if auxiliary containers are reachable by name"
    @parallel --tagstring "    - {}" --latest-line dig +time=1 +tries=1 +short ::: database mailpit keycloak && \
    echo "{{BOLD}}{{GREEN}}  [OK] All container names could be resolved.{{NORMAL}}" || \
    echo "{{BOLD}}{{RED}}  [WARN] Not all container names could be resolved!{{NORMAL}}"
    @echo "Checkin if all required auxiliary container ports are accessible from the main devcontainer"
    @parallel --tagstring "    - {}" --latest-line --colsep ':' nc -vz ::: \
    database:5432 mailpit:1025 keycloak:8280 && \
    echo "{{BOLD}}{{GREEN}}  [OK] All expected container ports could be reached.{{NORMAL}}" || \
    echo "{{BOLD}}{{RED}}  [WARN] Not all expected container ports could be reached!{{NORMAL}}"

# Kill all processes which use/block one of the known ports
[confirm("This will kill all processes shown with 'just why' - sure? [y/N]")]
stop:
    @echo "Stopping all processes listening to development ports"
    -fuser --verbose --kill {{fuser_known_ports}}

# Same as 'just stop' but ask for every process
stop-some:
    @echo "Selectively stopping processes listening to development ports"
    -fuser --verbose --kill --interactive {{fuser_known_ports}}
