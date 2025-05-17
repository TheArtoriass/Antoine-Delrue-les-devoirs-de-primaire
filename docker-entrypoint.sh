#!/bin/bash
set -e

# Attendre que MySQL soit prêt
until php -r "new PDO('mysql:host=db;dbname=sae_maintenance', 'root', 'root');" 2>/dev/null; do
  echo "⏳ Attente de la base de données..."
  sleep 2
done

# Lancer Apache
exec apache2-foreground