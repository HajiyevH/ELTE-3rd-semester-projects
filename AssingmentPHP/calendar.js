document.addEventListener('DOMContentLoaded', function() {
    const bookings = JSON.parse(document.getElementById('bookings-data').textContent);
    const today = new Date();
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    startDateInput.min = today.toISOString().split('T')[0];
    endDateInput.min = today.toISOString().split('T')[0];

    function isDateBooked(date) {
        return bookings.some(booking => {
            const bookingStart = new Date(booking.start_date);
            const bookingEnd = new Date(booking.end_date);
            return date >= bookingStart && date <= bookingEnd;
        });
    }

    function validateDates(input) {
        input.addEventListener('input', function() {
            const selectedDate = new Date(this.value);
            if (isDateBooked(selectedDate)) {
                this.setCustomValidity('This date is already booked.');
                this.classList.add('booked');
            } else {
                this.setCustomValidity('');
                this.classList.remove('booked');
            }
        });
    }

    validateDates(startDateInput);
    validateDates(endDateInput);
});