# Inventory Management MVP - Feature Specifications

Complete feature documentation for Category, Supplier, Product, and Stock Movement modules.

---

## Table of Contents

- [Feature 1: Categories](#feature-1-categories)
  - [Overview](#overview)
  - [Core Concepts](#core-concepts)
  - [Business Rules & Validation](#business-rules--validation)
  - [UI Components](#ui-components)
  - [Edge Cases](#edge-cases)
  - [Test Scenarios](#test-scenarios)
- [Feature 2: Suppliers](#feature-2-suppliers)
  - [Overview](#overview-1)
  - [Core Concepts](#core-concepts-1)
  - [Business Rules & Validation](#business-rules--validation-1)
  - [UI Components](#ui-components-1)
  - [Edge Cases](#edge-cases-1)
  - [Test Scenarios](#test-scenarios-1)
- [Feature 3: Products](#feature-3-products)
  - [Overview](#overview-2)
  - [Core Concepts](#core-concepts-2)
  - [Business Rules & Validation](#business-rules--validation-2)
  - [UI Components](#ui-components-2)
  - [Stock Management Strategy](#stock-management-strategy)
  - [Edge Cases](#edge-cases-2)
  - [Test Scenarios](#test-scenarios-2)
- [Feature 4: Stock Movements](#feature-4-stock-movements)
  - [Overview](#overview-3)
  - [Core Concepts](#core-concepts-3)
  - [Business Rules & Validation](#business-rules--validation-3)
  - [UI Components](#ui-components-3)
  - [Edge Cases](#edge-cases-3)
  - [Test Scenarios](#test-scenarios-3)
- [Cross-Feature Integration](#cross-feature-integration)
  - [Category → Product → Stock Movement Flow](#category--product--stock-movement-flow)
  - [Data Integrity Rules](#data-integrity-rules)
  - [Deletion Matrix](#deletion-matrix)
- [MVP Scope vs Future Enhancements](#mvp-scope-vs-future-enhancements)
  - [Implemented in MVP](#implemented-in-mvp)
  - [Future Features (Post-MVP)](#future-features-post-mvp)
- [Performance Checklist for Production](#performance-checklist-for-production)
  - [Database Optimizations](#database-optimizations)
  - [UI Optimizations](#ui-optimizations)
  - [Code Quality](#code-quality)
- [Testing Strategy](#testing-strategy)
  - [Unit Tests](#unit-tests)
  - [Feature Tests](#feature-tests)
  - [Browser Tests (Dusk)](#browser-tests-dusk)
- [Documentation Structure for Repo](#documentation-structure-for-repo)

---

## Feature 1: Categories

### Overview
Manage product classification taxonomy. Categories organize products into logical groups for easier navigation, reporting, and inventory analysis.

### Core Concepts
- **Hierarchical Organization**: Flat structure for MVP (no nested categories). Each product belongs to exactly one category.
- **Soft Deletes**: Categories can be deactivated without deleting associated products (products become "uncategorized").
- **Usage Prevention**: Cannot delete categories that contain active products to maintain data integrity.

### Business Rules & Validation
- **Name**: Required, min 3 chars, max 255 chars, unique across active categories
- **Description**: Optional, max 500 chars
- **Deletion Restriction**: `DELETE` operation blocked if `products.category_id` references the category
- **Restore Capability**: Soft-deleted categories can be restored, automatically reassigning products

### UI Components

**Main Screen:**
- Toolbar: Search box + "Add Category" button
- Table columns: Name, Description, Product Count, Status (Active/Inactive), Actions
- Each row shows badge with product count
- Status column uses colored badges (green=active, yellow=inactive)

**Modal Form:**
- Name input (required, placeholder: "e.g., Electronics")
- Description textarea (optional, placeholder: "Category details...")

### Edge Cases
- **Delete with Products**: Show error "Cannot delete category with N products. Reassign products first."
- **Duplicate Name**: Validation error "Category name already exists."
- **Restore Category**: Products with NULL category_id should be reassignable in bulk
- **Empty State**: Show "No categories found" with icon when table is empty

### Test Scenarios
1. Create category with valid data → Success
2. Create category with duplicate name → Validation error
3. Edit category name → Updates successfully, products remain assigned
4. Delete category with 0 products → Success, soft delete
5. Delete category with products → Error, no deletion
6. Search categories → Filters results in real-time
7. Restore soft-deleted category → Status changes to active

---

## Feature 2: Suppliers

### Overview
Manage vendor/supplier information for procurement tracking. Suppliers are the source of your inventory products.

### Core Concepts
- **Contact Management**: Store supplier contact details for purchase orders and communication
- **Soft Deletes**: Similar to categories - suppliers can be deactivated without losing product history
- **Product Linkage**: Track which products come from which supplier for reordering

### Business Rules & Validation
- **Name**: Required, min 3 chars, max 255 chars
- **Email**: Optional, must be valid email format if provided
- **Phone**: Optional, max 20 chars (no format validation for flexibility)
- **Address**: Optional, max 500 chars
- **Deletion Restriction**: Cannot delete if supplier has associated products

### UI Components

**Main Screen:**
- Toolbar: Search box + "Add Supplier" button
- Table columns: Name, Email, Phone, Product Count, Status, Actions
- Email/Phone show "-" when empty

**Modal Form:**
- Name input (required)
- Email input (type=email, optional)
- Phone input (optional)
- Address textarea (optional)

### Edge Cases
- **Multiple Suppliers**: A product can only have one supplier (MVP limitation). Future: Many-to-many relationship.
- **Missing Contact Info**: Allow suppliers with minimal data (just name) for quick setup
- **Supplier Deletion**: Products become "supplier-less" but remain in inventory
- **Email Validation**: Allow blank, but validate format if filled

### Test Scenarios
1. Create supplier with full details → Success
2. Create supplier with only name → Success
3. Edit supplier email → Updates without affecting products
4. Delete supplier with products → Error message
5. Search suppliers by name → Filters correctly
6. Add invalid email → Shows validation error

---

## Feature 3: Products

### Overview
Core entity of inventory system. Products are the individual items you track, manage, and sell.

### Core Concepts
- **SKU/Code**: Unique identifier for each product (required, unique)
- **Stock as Cached Value**: `current_stock` is a calculated field, not the source of truth
- **Initial Stock**: When creating product, automatically create first StockMovement
- **Relationships**: Belongs to one Category and one Supplier
- **No Direct Stock Edits**: Stock can only be changed via StockMovement records

### Business Rules & Validation
- **Name**: Required, min 3 chars, max 255 chars
- **Code**: Required, unique across all products (case-sensitive)
- **Unit Price**: Required, numeric, min 0 (use decimal 10,2)
- **Category**: Required, must exist in categories table
- **Supplier**: Required, must exist in suppliers table
- **Initial Stock**: Optional at creation, but if provided, creates StockMovement with reason "Initial product stock"
- **Edit Restriction**: Can edit all fields except stock quantity (stock changes only via transactions)

### UI Components

**Main Screen:**
- Toolbar: Search + "Add Product" button
- Table columns: Code, Name, Category, Supplier, Unit Price, Stock, Stock Status, Actions
- Stock column shows colored badge: red=<10, yellow warning=10-15, green=15+
- Status column: "Out of Stock" (red), "Low Stock" (yellow), "Available" (green)

**Modal Form:**
- Two-column layout: Code + Name (side by side)
- Category dropdown (required)
- Supplier dropdown (required)
- Unit Price input (number, step=0.01)
- Initial Stock input (only visible when creating new product, disabled when editing)
- Description textarea (optional)

### Stock Management Strategy
**Creation Flow:**
1. User fills product form including initial stock
2. Creates Product record with `current_stock = 0`
3. Immediately creates StockMovement with quantity = initial stock
4. `booted()` method updates product stock automatically

**Edit Flow:**
- Initial stock field is disabled
- Changing stock requires separate Stock In/Out transaction

### Edge Cases
- **Duplicate Code**: Show validation error "Product code already exists"
- **Insufficient Stock on Edit**: User cannot manually reduce stock in edit form
- **Category Deleted**: Product shows "Uncategorized" but remains functional
- **Supplier Deleted**: Product shows "No Supplier" but remains functional
- **Zero Price**: Allowed (for giveaways/samples), but shows warning
- **Zero Initial Stock**: Allowed for products not yet received

### Test Scenarios
1. Create product with initial stock 10 → Product stock = 10, 1 StockMovement created
2. Create product with initial stock 0 → Product stock = 0, no StockMovement
3. Edit product name → Updates successfully, stock unchanged
4. Try edit product initial stock → Field disabled
5. Search product by code → Finds correct product
6. Filter products by category → Shows only those products
7. Delete product with stock history → Error (or soft delete)
8. Product stock reaches 0 → Status shows "Out of Stock" badge

---

## Feature 4: Stock Movements

### Overview
The transaction layer that records every stock change. This is the single source of truth for inventory quantity.

### Core Concepts
- **Event-Driven Updates**: Model `booted()` method automatically syncs product stock
- **Immutability**: Movement records should not be edited after creation (audit trail)
- **Accountability**: Every movement linked to a user
- **Business Justification**: Every movement requires a reason

### Business Rules & Validation
- **Product**: Required, must exist
- **Type**: Required, enum ['in', 'out']
- **Quantity**: Required, integer, min=1, no max limit
- **Reason**: Required, min 5 chars, max 255 chars
- **User ID**: Automatically set to authenticated user
- **Notes**: Optional, max 500 chars
- **Insufficient Stock**: For 'out' type, validate `product.current_stock >= quantity`

### UI Components

**Main Screen:**
- Toolbar: Search (by product name) + Filter (All/In/Out) + "Record Transaction" button
- Table columns: Date, Product, Type, Quantity, Ending Stock, Reason, User
- Type column uses badges: green="In", yellow="Out"
- Ending Stock shows running balance per product (calculated in real-time)

**Modal Form:**
- Product dropdown: Shows "Product Name (Stock: X)" format
- Type select: "Stock In" or "Stock Out"
- Quantity number input (min=1)
- Reason text input with placeholder examples
- Notes textarea (optional)

### Edge Cases
- **Insufficient Stock**: Show error before submit, disable "Stock Out" if stock=0
- **Product Deleted**: Filter from dropdown, but show in history
- **User Deleted**: Show "Deleted User" in history table
- **Running Stock Calculation**: Can be slow with many records (paginate to mitigate)
- **Concurrent Modification**: Database transactions prevent race conditions

### Test Scenarios
1. Stock In 10 units → Product stock increases by 10
2. Stock Out 3 units → Product stock decreases by 3
3. Stock Out with insufficient stock → Error, no change
4. Create movement with empty reason → Validation error
5. Delete product after movements → Movements preserved, product_id remains
6. Two users create movements simultaneously → Both succeed or both fail (transaction)
7. View movement history → Shows all transactions with running stock

---

## Cross-Feature Integration

### **Category → Product → Stock Movement Flow**
1. Create Category ("Electronics")
2. Create Supplier ("Tech Vendor")
3. Create Product ("Laptop", category=Electronics, supplier=Tech Vendor, initial_stock=10)
   - Automatically creates StockMovement: type='in', quantity=10, reason="Initial product stock"
4. Create Stock In: quantity=5 → Product stock becomes 15
5. Create Stock Out: quantity=3 → Product stock becomes 12
6. Delete Category → Product's category_id becomes NULL, but product and stock history remain intact

### **Data Integrity Rules**
| Action | Effect on Related Records |
|--------|---------------------------|
| Delete Category | Products.category_id → NULL |
| Delete Supplier | Products.supplier_id → NULL |
| Delete Product | Blocked if stock_movements exist (use soft delete) |
| Delete User | stock_movements.user_id stays (soft delete users) |
| Delete StockMovement | Not allowed (audit trail) or soft delete only |

### **Deletion Matrix**
| Entity | Can Hard Delete? | Condition |
|--------|------------------|-----------|
| Category | No | Has products → soft delete or reassign first |
| Supplier | No | Has products → soft delete or reassign first |
| Product | No | Has stock history → soft delete only |
| StockMovement | No | Never delete (audit trail) |
| User | No | Use soft delete with `onDelete('restrict')` |

---

## MVP Scope vs Future Enhancements

### **Implemented in MVP**
✅ CRUD for Categories, Suppliers, Products  
✅ Stock In/Out transactions  
✅ Automatic stock updates via events  
✅ Basic search and filtering  
✅ Pagination for all tables  
✅ Soft deletes for Categories/Suppliers  
✅ STOCK OUT validation (prevent negative)  
✅ Audit trail with user tracking  
✅ Reason requirement for all movements  

### **Future Features (Post-MVP)**
- [ ] Barcode scanning integration
- [ ] Purchase order management
- [ ] Sales order integration
- [ ] Multi-location inventory
- [ ] Stock transfer between locations
- [ ] Batch import/export (CSV/Excel)
- [ ] Advanced reporting (date ranges, charts)
- [ ] Low stock email notifications
- [ ] User roles & permissions
- [ ] API endpoints for mobile app
- [ ] Inventory valuation methods (FIFO, LIFO)
- [ ] Product variants (size, color)
- [ ] Image upload for products

---

## Performance Checklist for Production

### **Database Optimizations**
- ✅ Indexes on foreign keys
- ✅ Indexes on searchable columns (name, code)
- [ ] Consider composite indexes for frequent queries
- [ ] Database query caching for dropdowns (categories, suppliers)
- [ ] Redis for session cache (optional)

### **UI Optimizations**
- ✅ Pagination (default 10-15 items)
- [ ] Lazy loading for relationships
- [ ] Debounce search (300ms)
- [ ] Virtual scroll for large tables (if needed)

### **Code Quality**
- ✅ Validation rules centralized
- [ ] Extract service classes when duplication > 3x
- [ ] Write feature tests (Pest/PHPUnit)
- [ ] Add PHPDoc comments
- [ ] Implement repository pattern (if needed)

---

## Testing Strategy

### **Unit Tests**
- Test model relationships
- Test booted() event logic
- Test validation rules
- Test scope queries (withTrashed, search)

### **Feature Tests**
- Complete CRUD flow for each entity
- Stock movement transaction integrity
- Concurrent stock updates
- Search functionality
- Soft delete behavior

### **Browser Tests (Dusk)**
- Modal opens/closes correctly
- Form validation shows errors
- Pagination works
- Search filters in real-time

---

## Documentation Structure for Repo

```
/docs
├── features
│   ├── categories.md
│   ├── suppliers.md
│   ├── products.md
│   └── stock-movements.md
├── api
│   └── endpoints.md (future)
├── database
│   ├── erd.md
│   └── migrations.md
└── testing
    └── test-cases.md
```

Each feature doc should include:
- Feature overview
- Business rules
- UI mock description
- Validation rules
- Edge cases
- Test scenarios
- Future improvements