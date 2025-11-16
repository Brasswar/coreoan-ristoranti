#!/bin/bash

# ============================================================================
# Deploy Script - Gestionale Ristorante
# ============================================================================
# Usage: ./deploy.sh [command]
# Commands:
#   setup    - First time setup (build + migrations + seed)
#   build    - Build the Docker image
#   start    - Start the containers
#   stop     - Stop the containers
#   restart  - Restart the containers
#   logs     - View logs
#   migrate  - Run migrations
#   seed     - Run seeders
#   fresh    - Fresh migration + seed (WARNING: deletes all data!)
#   shell    - Open a bash shell in the container
#   artisan  - Run artisan command (e.g., ./deploy.sh artisan route:list)
#   optimize - Optimize Laravel (cache config, routes, views)
#   clear    - Clear all Laravel caches
#   backup   - Backup the database
#   status   - Show containers status
# ============================================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Container name
CONTAINER_NAME="ristorante-frontend"

# Functions
function print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

function print_error() {
    echo -e "${RED}✗ $1${NC}"
}

function print_info() {
    echo -e "${YELLOW}ℹ $1${NC}"
}

function check_container() {
    if ! docker ps -q -f name=${CONTAINER_NAME} | grep -q .; then
        print_error "Container ${CONTAINER_NAME} is not running!"
        exit 1
    fi
}

# Commands
case "$1" in
    setup)
        print_info "Starting first time setup..."

        print_info "Creating storage directories..."
        mkdir -p frontend/storage/framework/{cache/data,sessions,views}
        mkdir -p frontend/storage/logs
        mkdir -p frontend/bootstrap/cache

        print_info "Building Docker image..."
        docker-compose build frontend

        print_info "Starting container..."
        docker-compose up -d frontend

        sleep 5

        print_info "Generating APP_KEY..."
        docker exec ${CONTAINER_NAME} php artisan key:generate

        print_info "Running migrations..."
        docker exec ${CONTAINER_NAME} php artisan migrate

        print_info "Seeding database..."
        docker exec ${CONTAINER_NAME} php artisan db:seed

        print_info "Optimizing Laravel..."
        docker exec ${CONTAINER_NAME} php artisan optimize

        print_success "Setup completed!"
        print_info "Application is available at: https://app.coreaon.org"
        print_info "Login with: admin@test.com / password"
        ;;

    build)
        print_info "Building Docker image..."
        docker-compose build frontend
        print_success "Build completed!"
        ;;

    start)
        print_info "Starting containers..."
        docker-compose up -d frontend
        print_success "Containers started!"
        docker-compose ps frontend
        ;;

    stop)
        print_info "Stopping containers..."
        docker-compose stop frontend
        print_success "Containers stopped!"
        ;;

    restart)
        print_info "Restarting containers..."
        docker-compose restart frontend
        print_success "Containers restarted!"
        docker-compose ps frontend
        ;;

    logs)
        print_info "Showing logs (Ctrl+C to exit)..."
        docker-compose logs -f frontend
        ;;

    migrate)
        check_container
        print_info "Running migrations..."
        docker exec ${CONTAINER_NAME} php artisan migrate
        print_success "Migrations completed!"
        ;;

    seed)
        check_container
        print_info "Running seeders..."
        docker exec ${CONTAINER_NAME} php artisan db:seed
        print_success "Seeding completed!"
        ;;

    fresh)
        check_container
        print_error "WARNING: This will delete all data!"
        read -p "Are you sure? (yes/no): " confirm
        if [ "$confirm" = "yes" ]; then
            print_info "Running fresh migration..."
            docker exec ${CONTAINER_NAME} php artisan migrate:fresh --seed
            print_success "Fresh migration completed!"
        else
            print_info "Cancelled."
        fi
        ;;

    shell)
        check_container
        print_info "Opening shell in container..."
        docker exec -it ${CONTAINER_NAME} /bin/bash
        ;;

    artisan)
        check_container
        shift
        print_info "Running artisan $@..."
        docker exec ${CONTAINER_NAME} php artisan "$@"
        ;;

    optimize)
        check_container
        print_info "Optimizing Laravel..."
        docker exec ${CONTAINER_NAME} php artisan optimize
        docker exec ${CONTAINER_NAME} php artisan config:cache
        docker exec ${CONTAINER_NAME} php artisan route:cache
        docker exec ${CONTAINER_NAME} php artisan view:cache
        print_success "Optimization completed!"
        ;;

    clear)
        check_container
        print_info "Clearing all caches..."
        docker exec ${CONTAINER_NAME} php artisan cache:clear
        docker exec ${CONTAINER_NAME} php artisan config:clear
        docker exec ${CONTAINER_NAME} php artisan route:clear
        docker exec ${CONTAINER_NAME} php artisan view:clear
        print_success "Caches cleared!"
        ;;

    backup)
        print_info "Backing up database..."
        BACKUP_FILE="backup_ristorante_$(date +%Y%m%d_%H%M%S).sql"
        docker exec mysql mysqldump -uroot -p${MYSQL_ROOT_PASSWORD} ristorante_db > ${BACKUP_FILE}
        print_success "Backup saved to: ${BACKUP_FILE}"
        ;;

    status)
        print_info "Container status:"
        docker-compose ps frontend
        echo ""
        print_info "Health check:"
        curl -f http://localhost:3000/up && print_success "Health check OK" || print_error "Health check FAILED"
        ;;

    *)
        echo "Usage: $0 {setup|build|start|stop|restart|logs|migrate|seed|fresh|shell|artisan|optimize|clear|backup|status}"
        echo ""
        echo "Commands:"
        echo "  setup    - First time setup (build + migrations + seed)"
        echo "  build    - Build the Docker image"
        echo "  start    - Start the containers"
        echo "  stop     - Stop the containers"
        echo "  restart  - Restart the containers"
        echo "  logs     - View logs"
        echo "  migrate  - Run migrations"
        echo "  seed     - Run seeders"
        echo "  fresh    - Fresh migration + seed (WARNING: deletes all data!)"
        echo "  shell    - Open a bash shell in the container"
        echo "  artisan  - Run artisan command (e.g., $0 artisan route:list)"
        echo "  optimize - Optimize Laravel (cache config, routes, views)"
        echo "  clear    - Clear all Laravel caches"
        echo "  backup   - Backup the database"
        echo "  status   - Show containers status"
        exit 1
        ;;
esac
