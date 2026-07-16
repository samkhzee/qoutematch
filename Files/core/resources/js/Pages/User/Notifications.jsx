import MasterLayout from '@/Components/Layout/MasterLayout';
import NotificationInbox from '@/Components/Shared/NotificationInbox';

export default function Notifications({ pageTitle, logs }) {
    return (
        <MasterLayout pageTitle={pageTitle}>
            <div className="container-fluid px-0">
                <div className="dashboard-body-wrapper mt-4">
                    <NotificationInbox logs={logs} />
                </div>
            </div>
        </MasterLayout>
    );
}
