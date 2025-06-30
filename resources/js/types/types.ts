export type AuthUser = {
    id: number;
    name: string;
    email: string;
    role: string;
    created_at: string;
    updated_at: string;
    token: string;
}

export type Price = {
    price: number;
    year: number;
}

export type Role = 'admin' | 'manager' | 'sales';

export type Product = {
    code: string;
    description: string;
    created_at: string;
    updated_at: string;
    current_price: number;
    prices: Price[];
}

export type ProductRequest = {
    code: string;
    description: string;
    action: 'add' | 'edit';
}

export type VendorType = 'supplier' | 'distributor';

export type Contact = {
    id: number;
    name: string;
    email: string;
    phone: string;
    role: 'sales' | 'logistics';
}

export type Vendor = {
    id: number;
    name: string;
    address: string;
    country: string;
    vat_number: string;
    type: VendorType;
    sales_contact: Contact;
    logistics_contact: Contact;
    created_at: string;
    updated_at: string;
}

export type VendorRequest = {
    id: number | null;
    name: string;
    address: string;
    country: string;
    vat_number: string;
    type: VendorType;
    sales_contact_id: number | null;
    logistics_contact_id: number | null;
    action: 'add' | 'edit';
}

export type PurchaseOrderStatus = 'new' | 'accepted_by_supplier' | 'in_delivery' | 'delivered' | 'rejected_by_supplier' | 'rejected_by_distributor' | 'cancelled';

export type PurchaseOrder = {
    po_id: string;
    id: number;
    vendor_id: number;
    vendor: Vendor;
    product_code: string;
    product: Product;
    quantity: number;
    delivery_date: string;
    total: number;
    status: PurchaseOrderStatus;
    created_at: string;
    updated_at: string;
}

export type PurchaseOrderRequest = {
    po_id: string | null;
    id: number | null;
    vendor_id: number | null;
    product_code: string;
    quantity: number;
    delivery_date: string;
    status: PurchaseOrderStatus;
    action: 'add' | 'edit';
}