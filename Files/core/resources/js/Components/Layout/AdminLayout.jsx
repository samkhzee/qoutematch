/**
 * Admin React pages render inside the Blade admin shell (admin/inertia.blade.php).
 * This wrapper only provides a consistent content container — no duplicate nav.
 */
export default function AdminLayout({ children }) {
    return <div className="admin-react-panel">{children}</div>;
}
