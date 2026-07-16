import { Component } from 'react';

export default class InertiaErrorBoundary extends Component {
    constructor(props) {
        super(props);
        this.state = { hasError: false };
    }

    static getDerivedStateFromError() {
        return { hasError: true };
    }

    componentDidCatch(error, info) {
        console.error('Page render error:', error, info);
    }

    render() {
        if (this.state.hasError) {
            return (
                <div className="container py-5 text-center connection-error-panel">
                    <div className="connection-error-panel__icon mb-3" aria-hidden="true">
                        <i className="las la-wifi" />
                    </div>
                    <h4 className="mb-3">We couldn&apos;t load this page</h4>
                    <p className="text-muted mb-2">
                        This usually happens when your connection is slow or briefly interrupted.
                    </p>
                    <p className="text-muted mb-4">
                        Check your internet connection, then reload the page to try again.
                    </p>
                    <button type="button" className="btn btn--base" onClick={() => window.location.reload()}>
                        Reload page
                    </button>
                </div>
            );
        }

        return this.props.children;
    }
}
