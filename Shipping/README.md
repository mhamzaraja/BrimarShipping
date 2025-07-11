# Brimar Shipping Extension for Magento 2

A custom shipping method extension that allows administrators to create multiple shipping options with dynamic pricing and labels. Customers can select from available shipping options during checkout with full validation and order tracking.

## Features

- ✅ **Custom Shipping Method** - Create "Brimar Shipping" as a configurable shipping method
- ✅ **Multiple Shipping Options** - Add/edit/remove multiple shipping options with custom labels and prices
- ✅ **Admin Management** - Full CRUD operations for shipping options via admin panel
- ✅ **Checkout Integration** - Dynamic option selection during checkout with validation
- ✅ **Order Synchronization** - Selected shipping option appears in emails, admin orders, and customer dashboard
- ✅ **Price Updates** - Real-time price and label updates in order summary
- ✅ **Validation System** - Prevents checkout progression without option selection

## Installation

### Method 1: Manual Installation (Recommended)

1. **Downloadthe extension:**

   Navigate to your Magento root directory
   Copy extension file to `app/code/`


2. **Enable the module:**
   php bin/magento module:enable Brimar_Shipping
   php bin/magento setup:upgrade
   php bin/magento setup:di:compile
   php bin/magento setup:static-content:deploy
   php bin/magento cache:clean
   php bin/magento cache:flush

3. **Verify installation:**
   php bin/magento module:status Brimar_Shipping
   Should show: `Brimar_Shipping [enabled]`


## Configuration

### 1. Enable Brimar Shipping Method

1. **Go to Admin Panel:**
   - Navigate to `Stores → Configuration → Sales → Shipping Methods`
   - Find "Brimar Shipping" section
   - Set `Enabled` to "Yes"
   - Configure other settings as needed:
     - Title: "Brimar Shipping"
     - Method Name: "Brimar Shipping"
     - Price: Base shipping price
     - Ship to Applicable Countries
   - Click `Save Config`

### 2. Manage Shipping Options

1. **Go to Admin Panel:**
   - Navigate to `Sales → Brimar Shipping Options`
   - Click "Add Shipping Options" button

2. **Add New Option:**
   - **Code:** Unique identifier (e.g., "residential", "scheduled")
   - **Label:** Display name (e.g., "Residential Delivery", "Scheduled Delivery")
   - **Price:** Additional shipping cost
   - **Is Active:** Enable/disable the option
   - Click "Save"

3. **Manage Existing Options:**
   - View all options in the grid
   - Edit by clicking on any row
   - Delete using the Actions dropdown
   - Inline edit for quick changes

## Testing Guide

### Frontend Testing

1. **Add products to cart**
2. **Go to checkout**
3. **In shipping step:**
   - Select "Brimar Shipping" as shipping method
   - Verify shipping options appear below
   - Try to click "Next" without selecting option
   - **Expected:** Red error message appears, cannot proceed
4. **Select any shipping option:**
   - **Expected:** Error disappears, can proceed to next step
   - **Expected:** Order summary updates with selected option name and price
5. **Complete the order**
6. **Verify in:**
   - Order confirmation email (shipping method should show selected option)
   - Customer dashboard (My Orders)
   - Admin sales orders

### Backend Testing

1. **System Configuration:**
   - Test enabling/disabling Brimar shipping
   - Verify configuration saves correctly

2. **Shipping Options Management:**
   - Create new shipping option
   - Edit existing option
   - Delete option
   - Test inline editing
   - Verify validation (required fields, unique codes)

3. **Order Management:**
   - View orders with Brimar shipping in admin
   - Verify shipping method displays correctly
   - Check order emails contain correct shipping info

