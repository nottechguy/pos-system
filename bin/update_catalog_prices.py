#!/usr/bin/env python3
#
# Copyright (c) Abarrotes Todo En Uno
#
# Catalog Pricing Script

import os
import sys
import re
import json
import argparse
import urllib.request
from urllib.error import HTTPError, URLError

API_URL = "http://127.0.0.1:8000/api/products/sync"
API_TOKEN = "1|VULUvcLmkilaPUGXohXMinKLIsuR6F1EewWoov8Mc875f06b" # Replace with valid API token

def console_print(st="", f=sys.stdout, linebreak=True):
    f.write(st)
    if linebreak:
        f.write(os.linesep)
    f.flush()

def sync_product(payload):
    req = urllib.request.Request(API_URL, data=json.dumps(payload).encode('utf-8'))
    req.add_header('Content-Type', 'application/json')
    req.add_header('Accept', 'application/json')
    req.add_header('Authorization', f'Bearer {API_TOKEN}')
    
    try:
        with urllib.request.urlopen(req) as response:
            return response.getcode() == 200
    except HTTPError as e:
        console_print(f"  [!] HTTP Error {e.code}: {e.read().decode('utf-8')}", sys.stderr)
        return False
    except URLError as e:
        console_print(f"  [!] Network Error: {e.reason}", sys.stderr)
        return False

def parse_catalog(filepath, target_dept, update_mode):
    dept_pattern = re.compile(r'^\[(DEP-\d+)\s+"([^"]+)"\]')
    # Updated pattern to allow box notation: barcode->component:qty
    product_pattern = re.compile(r'^(!?[A-Za-z0-9\->:]+)\s+"([^"]+)"\s+"([^"]+)"\s+"([^"]+)"\s+"([^"]+)"\s+"([^"]+)"\s+([\d.]+)\s+([\d.]+)\s+([\d.]+)')

    current_dept_code = None
    current_dept_name = None

    if not os.path.exists(filepath):
        console_print(f"File not found: {filepath}")
        return

    with open(filepath, 'r', encoding='utf-8') as f:
        for line in f:
            line = line.strip()
            if not line or line.startswith('#'):
                continue

            # Check for department header
            dept_match = dept_pattern.match(line)
            if dept_match:
                current_dept_code = dept_match.group(1)
                current_dept_name = dept_match.group(2)
                continue
            
            # Skip if filtering by department and it doesn't match
            if target_dept and current_dept_code != target_dept:
                continue

            # Parse product line
            prod_match = product_pattern.match(line)
            if prod_match:
                raw_barcode_field = prod_match.group(1)
                allows_fraction = raw_barcode_field.startswith('!')
                clean_field = raw_barcode_field.lstrip('!')

                box_component_barcode = None
                box_quantity = None
                barcode = clean_field

                # Check if box mapping notation is present (e.g. box->item:qty)
                if '->' in clean_field and ':' in clean_field:
                    parts = clean_field.split('->')
                    barcode = parts[0]
                    subparts = parts[1].split(':')
                    box_component_barcode = subparts[0]
                    box_quantity = float(subparts[1])

                payload = {
                    "department_code": current_dept_code,
                    "department_name": current_dept_name,
                    "barcode": barcode,
                    "box_component_barcode": box_component_barcode,
                    "box_quantity": box_quantity,
                    "name": prod_match.group(2),
                    "category_name": prod_match.group(3),
                    "product_type": prod_match.group(4),
                    "sale_unit_code": prod_match.group(5),
                    "inventory_unit_code": prod_match.group(6),
                    "cost_price": float(prod_match.group(7)),
                    "sale_price": float(prod_match.group(8)),
                    "allows_fractional_qty": allows_fraction,
                    "update_mode": update_mode
                }

                console_print(f"Syncing: {barcode} - {payload['name'][:50]}...", linebreak=False)
                if sync_product(payload):
                    # Print bold green OK (ANSI escape codes: \033[1;32m = Bold Green, \033[0m = Reset)
                    console_print(" \033[1;32mOK\033[0m")
                else:
                    console_print(" \033[1;31mFAILED\033[0m")

def main():
    parser = argparse.ArgumentParser(description="Abarrotes Todo En Uno Catalog Sync Tool")
    parser.add_argument('file', help="Path to catalog.dat file")
    parser.add_argument('-u', '--update', action='store_true', help="Force update existing records")
    parser.add_argument('-d', '--dept', type=str, help="Filter sync to a specific department code (e.g. DEP-001)")
    
    args = parser.parse_args()
    
    console_print("========================================")
    console_print(" Starting Catalog Synchronization")
    console_print("========================================")
    
    parse_catalog(args.file, args.dept, args.update)
    
    console_print("Done.")
    return 0

if __name__ == '__main__':
    sys.exit(main())