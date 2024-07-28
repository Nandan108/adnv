export const fetchJson = async (query, options = {}) => {
  const defaultOptions = {
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
  };

  const finalOptions = { ...defaultOptions, ...options };

  try {
    const response = await fetch(query, finalOptions);

    if (!response.ok) {
      const errorText = await response.text();
      throw new Error(`Request failed with status ${response.status}: ${errorText}`);
    }

    return await response.json();
  } catch (error) {
    console.error('Fetch error:', error);
    throw error;
  }
};

export const chfHtml = (number, decimals = 2, currency = false) => {
  if (number === null) return '';
  number = parseFloat(number);
  let options = {
    minimumFractionDigits: decimals,
    ...(currency ? { style: 'currency', currency: 'CHF' } : {}),
  };
  return new Intl.NumberFormat('fr-CH', options).format(number);
};

export const uc1st = (str) => {
  return str.charAt(0).toUpperCase() + str.slice(1);
};

