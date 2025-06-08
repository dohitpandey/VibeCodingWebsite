class VisitorCounter {
    constructor() {
        this.element = document.getElementById('visitor-number');
        this.updateCount();
        setInterval(() => this.updateCount(), 300000); // 5 minutes
    }

    async updateCount() {
        try {
            const response = await fetch('counter.php');
            const data = await response.json();
            if (data.status === 'success') {
                this.element.textContent = data.count;
            } else {
                console.error('Update failed:', data.message);
            }
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => new VisitorCounter());
