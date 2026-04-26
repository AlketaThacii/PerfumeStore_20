const counters = document.querySelectorAll('.counter');

counters.forEach(counter => {
  const target = Number(counter.getAttribute('data-target'));
  let count = 0;
  const increment = Math.ceil(target / 40);

  const updateCounter = () => {
    count += increment;

    if (count < target) {
      counter.textContent = count + '+';
      setTimeout(updateCounter, 30);
    } else {
      counter.textContent = target + '+';
    }
  };

  updateCounter();
});
