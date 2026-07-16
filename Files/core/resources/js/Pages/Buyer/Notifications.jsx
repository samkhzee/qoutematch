import BuyerMasterLayout from '@/Components/Layout/BuyerMasterLayout';
import NotificationInbox from '@/Components/Shared/NotificationInbox';

export default function Notifications({ pageTitle, logs }) {
    return (
        <BuyerMasterLayout pageTitle={pageTitle}>
            <div className="container-fluid px-0">
                <div className="dashboard-body-wrapper mt-4">
                    <NotificationInbox logs={logs} />
                </div>
            </div>
        </BuyerMasterLayout>
    );
}
